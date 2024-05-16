const addFormToCollection = (e) => {
    const collection_holder = document.querySelector(e.currentTarget.dataset.collection);
    const item = document.createElement('div');
    item.className = 'mt-3 bg-warning p-3';
    item.innerHTML = collection_holder
        .dataset
        .prototype
        .replace(/__name__/g, collection_holder.dataset.index);
        let btn_supprimer = document.createElement('button');
        btn_supprimer.className = 'btn btn-danger lt-3 btn-supprimer';
        btn_supprimer.id = 'btn-supprimer';
        btn_supprimer.innerHTML = 'X';
        item.appendChild(btn_supprimer);

    collection_holder.appendChild(item);
    collection_holder.dataset.index++;

    document.querySelectorAll('.btn-supprimer').forEach(btn => btn.addEventListener('click', (e) => 
        e.currentTarget.parentElement.remove()));
}

document.querySelectorAll('.btn-ajouter').forEach(btn => btn.addEventListener('click', addFormToCollection));