/*
 * MOBILE MENU
 */

// BEGIN MOBILE MENU
const appSidebar = document.querySelector(".app_sidebar");

document.querySelector("li[data-mobilemenu]").addEventListener('click', function () {
    const action = this.dataset.mobilemenu;

    if (action === 'open') {
        appSidebar.style.display = 'block';
    }
});

document.querySelector("div[data-mobilemenu]").addEventListener('click', function () {
    const action = this.dataset.mobilemenu;

    if (action === 'close') {
        appSidebar.style.display = 'none';
    }
});
// END MOBILE MENU


// BEGIN SUB MENUS
// FUNCTION SIDEBAR

// RODA UM LOOPING COM OS SUBMENUS QUE DEVEM SER ESCONDIDOS
function hideBar(bars) {
    for (bar of bars) {
        let toHide = document.querySelector(bar);
        toHide.style.display = 'none';
    }
}

// REALIZA A ROTINA DE MOSTRAR E ESCONDER OS SUBMENUS PASSANDO O MENU A SER CLICADO E O ID DO CONSJUNTO DE SUB MENUS
function sideBar(nameEventId, appDrop) {
    document.getElementById(nameEventId).addEventListener('click', function () {
        const clickedClassList = this.classList;
        const $appDrop = document.querySelector(appDrop);

        if (! clickedClassList.contains('open')) {
            $appDrop.classList.add('slidedown');
            clickedClassList.add('open');
            $appDrop.style.display = 'block';
        } else {
            $appDrop.classList.add('slideup');
            clickedClassList.remove('open');
            $appDrop.style.display = 'none';
        }
    });
}

// APLICAÇÃO DO HIDER
hideBar([".drop_moviment_create", ".drop_moviment_read", ".drop_manager_create", ".drop_manager_read"]);


// APLICAÇÃO DE TODOS OS MENUS

// MOVIMENTAÇÃO
sideBar('sidebar', ".app_drop");
// childrens
sideBar('open_moviment_create', ".drop_moviment_create");
sideBar('open_moviment_read', ".drop_moviment_read");

// MOVIMENTAÇÃO
sideBar('sidebar2', ".app_drop1");

// GERENCIAL
sideBar('sidebar3', ".app_drop2");
// childrens
sideBar('open_manager_create', ".drop_manager_create");
sideBar('open_manager_read', ".drop_manager_read");
// END SUBMENUS

