let warningTimeout;
let sessionTimeout;

function fetchSessionInfo()
{
    return $.ajax({
        url: 'index.php?page=config',
        method: 'POST',
        data:{
            action:'getSessionInfo'
        },
        dataType: 'json'
    });
}

function startSessionTimers(sessionInfo)
{
    const warningTime = 2 * 60 * 1000;
    const sessionDuration = sessionInfo.sessionTime * 60 * 1000;

    clearTimeout(warningTimeout);
    clearTimeout(sessionTimeout);

    warningTimeout = setTimeout(showWarningModal, sessionDuration - warningTime);

}


function showWarningModal()
{
    $("#sessionModal").modal({
        backdrop:'static'
    })
    $("#sessionModal").modal('show')
}

function extendSession()
{
    $('#sessionModal button').prop("disabled",true)
    $.ajax({
        url: 'index.php?page=config',
        method: 'POST',
        data:{
            action:'extendSession'
        },
        dataType: 'json',
        success: (data) => {
            console.log(data)
            if (data.sessionExtended) {
                fetchSessionInfo().then((sessionInfo) => {
                    startSessionTimers(sessionInfo);
                    $('#sessionModal button').prop("disabled", false);
                    $('#sessionModal').modal('hide');
                });
            } else {
                window.location.reload();
            }
        },
        error: (error) => {
            console.error(error);
            window.location.reload();
        }

    });
}

function handleSessionExpired()
{
    $('#sessionModal button').prop("disabled",true)
    $.ajax({
        url: 'index.php?page=config',
        method: 'POST',
        data:{
            action:'sessionExpired'
        },
        dataType: 'json',
        success: (data) => {
            if (data.sessionExpired) {
                window.location.reload();
            }
        },
        error: (error) => {
            console.error(error);
            window.location.reload();
        }

    });
}

$(document).ready(() => {
    fetchSessionInfo().then((sessionInfo) => {
        if (!sessionInfo.userNotLoggedIn) {
            startSessionTimers(sessionInfo);
        }
    });

});