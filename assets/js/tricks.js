//  This handles the 'add' button for videos and illustrations in trick form
document
    .querySelectorAll('.add_illustration_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });

document
    .querySelectorAll('.add_video_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection);
    });

function addFormToCollection(e) {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');
    item.innerHTML =
        collectionHolder
            .dataset
            .prototype
            .replace(/__name__/g, collectionHolder.dataset.index);

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;
}
