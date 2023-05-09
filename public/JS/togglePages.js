
class TogglePages {

    static header = document.querySelector("h1");

    pages = [];
    activePage = 0;
    displayType = 'block';


    constructor(pages,displayType = 'block') {
         for(let i = 0; i < pages.length; i++) {

             let btn = document.getElementById(pages[i].btnId);
            this.pages.push(
                {
                    btn: btn,
                    page: document.getElementById(pages[i].pageId),
                    title: pages[i].title ? pages[i].title : null
                }
            );

            if(btn) {
                btn.addEventListener('click', (e) => {
                    this.togglePages(e);
                });
            }

         }
            this.displayType = displayType;

    }

    togglePages(e) {

        let element = e.target;
        while(element.classList.contains('page') === false) {
            element = element.parentElement;
        }
        this.pages[this.activePage].page.style.display = 'none';
        this.pages[this.activePage].btn.classList.remove('active-heading-page');

        this.activePage = this.pages.findIndex((page) => {
            return page.btn === element;
        });

        this.pages[this.activePage].page.style.display = this.displayType;
        this.pages[this.activePage].btn.classList.add('active-heading-page');

        if(this.pages[this.activePage]['title']) {
            TogglePages.header.innerHTML = this.pages[this.activePage]['title']
        }

    }

    showPage(page) {
        page.style.display = 'block';
    }

    hidePage(page) {
        page.style.display = 'none';
    }

    getActivePage() {
        return this.pages[this.activePage];
    }

    removeNoData() {
        this.pages.forEach((page) => {

            const img = page['page'].querySelector('img');

            if(!img) {
                return;
            }

            img.remove();

            if(page['page'].className !== 'content') {
                page['page'].classList.add('card-container');
            }
            page['page'].classList.remove('no-data');
        });
    }

    checkNoData() {

        this.pages.forEach((page) => {

            const noDataImg = document.createElement('img');
            noDataImg.src = '/Commusupport/public/src/errors/NoData.svg';

            if(page['page'].innerHTML === '') {
                if(page['page'].className !== 'content') {
                    page['page'].classList.remove('card-container');
                }
                page['page'].classList.add('no-data');
                page['page'].append(noDataImg);
            } else {

            }
        });
    }
}

export default TogglePages;