
class TogglePages {

    pages = [];
    activePage = 0;
    displayType = 'block';


    constructor(pages,displayType = 'block') {
         for(let i = 0; i < pages.length; i++) {
             // console.log(pages[i]);
             let btn = document.getElementById(pages[i].btnId);
            this.pages.push(
                {
                    btn: btn,
                    page: document.getElementById(pages[i].pageId)
                }
            );
            btn.addEventListener('click', (e) => {
                this.togglePages(e);
            });
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
}

export default TogglePages;