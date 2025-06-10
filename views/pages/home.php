<div class="mx-auto h-100 d-flex flex-column text-bg-white"  style="background-color: rgba(245, 245, 245, 0); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);backdrop-filter: blur(8.7px);">
    <div class="m-auto text-center">
        <img src="public\img\logo-imt.png" alt="Logo IMT Mines Alès" class="img-fluid" style="width: 200px;">
        <h1 class="my-4 font-weight-bold">Syllabus IMT Mines Alès</h1>
        <p class="lead px-4">Bienvenue sur le système de gestion de syllabus d'IMT Mines Alès. Ce système digitalisé permet de gérer de manière centralisée les documents pédagogiques des formations d'ingénieur. Accédez aux syllabus des formations, recherchez les informations clés et générez des documents PDF conformes aux exigences de la CTI. </p>
        <p class="lead px-4">Le système est conçu pour simplifier la consultation et l'actualisation des informations pédagogiques.</p>
    </div>

    <?php if ($_SERVER['HTTP_HOST'] != 'syllabus.mines-ales.fr') : ?>
        <div class="m-auto text-center">
            <p class="alert alert-danger" role="alert">
                Attention, vous n'êtes pas sur le site officiel du Syllabus IMT Mines Alès.<br/> Ceci est la version de test
            </p>
        </div>
    <?php endif; ?>
    
</div>