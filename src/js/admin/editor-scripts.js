window.addEventListener('load', (e) => {
    const eefElementorPanelEditor = document.getElementById('elementor-panel-content-wrapper');
    if (!eefElementorPanelEditor) {
        return;
    }

    const observer = new MutationObserver((mutations, observer) => {
        const actionRegisterPostSection = eefElementorPanelEditor.querySelector('.elementor-control-eef-register-post-section');
        if (!actionRegisterPostSection) {
            return;
        }
        
        if(!actionRegisterPostSection.classList.contains('elementor-hidden-control') ) {
            const effActionControls = eefElementorPanelEditor.querySelectorAll('.elementor-control-eef-register-post-field');
            if (effActionControls.length > 0) {
                effActionControls.forEach((item) => {
                    item.classList.remove('elementor-hidden-control');
                });
            }
        } else {
            const effActionControls = eefElementorPanelEditor.querySelectorAll('.elementor-control-eef-register-post-field');
            const effActionControls2 = eefElementorPanelEditor.querySelectorAll('.elementor-control-eef-register-post-custom-field');
            if (effActionControls.length > 0) {
                effActionControls.forEach((item) => {
                    item.classList.add('elementor-hidden-control');
                });
            }

            if (effActionControls2.length > 0) {
                effActionControls2.forEach((item) => {
                    if (!item.classList.contains('elementor-hidden-control')) {
                        item.classList.add('elementor-hidden-control');
                    }
                });
            }
        }
    });
    
    observer.observe(eefElementorPanelEditor, {
        childList: true,
        subtree: true,
        attributes: false,
        characterData: false,
    });
});