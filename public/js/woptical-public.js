let wopticalAccordion = '',
    wopticalOptions = '';

function wopticalLoaded() {
    console.log('Function Loaded');


    wopticalOptions = document.getElementsByClassName('custom-option-item');
    if (wopticalOptions) {
        for (let index = 0; index < wopticalOptions.length; index++) {
            wopticalOptions[index].addEventListener('click', function(e) {
                e.stopPropagation();
                var label = wopticalOptions[index].getAttribute('data-label');
                var headingId = wopticalOptions[index].parentElement.previousElementSibling.getAttribute('id');
                var labelSpan = document.querySelector('#' + headingId + ' span').innerHTML;
                document.querySelector('#' + headingId + ' span').innerHTML = '';
                document.querySelector('#' + headingId + ' span').innerHTML = labelSpan + ' ' + label;
                document.querySelector('#' + headingId + ' span').classList.remove('selected-hidden');
            });
        }
    }

    wopticalAccordion = document.getElementsByClassName('custom-option-title');
    if (wopticalAccordion) {
        for (let index = 0; index < wopticalAccordion.length; index++) {
            const element = wopticalAccordion[index];
            element.addEventListener('click', function() {
                for (let indexY = 0; indexY < wopticalAccordion.length; indexY++) {
                    wopticalAccordion[indexY].parentElement.classList.remove('show');
                }
                this.parentElement.classList.toggle('show');
            });
        }
    }

    wopticalTabContent = document.getElementsByClassName('custom-optical-tab-item');
    wopticalTab = document.getElementsByClassName('tab-link');
    if (wopticalTab) {
        for (let index = 0; index < wopticalTab.length; index++) {
            const element = wopticalTab[index];
            element.addEventListener('click', function(e) {
                e.preventDefault();
                var linkid = this.getAttribute('id');

                for (let indexY = 0; indexY < wopticalTab.length; indexY++) {
                    wopticalTab[indexY].classList.remove('tab-active');
                }

                this.classList.add('tab-active');

                for (let indexZ = 0; indexZ < wopticalTabContent.length; indexZ++) {
                    wopticalTabContent[indexZ].classList.add('tab-hidden');
                }
                const selectedTab = document.querySelector('[data-button="' + linkid + '"]');
                selectedTab.classList.remove('tab-hidden');
            });
        }
    }

}

document.addEventListener("DOMContentLoaded", wopticalLoaded, false);