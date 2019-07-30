document.addEventListener("DOMContentLoaded", function () {
    var collections = {};

    // Prepare collections first
    var available_collections = document.querySelectorAll('[data-collection]');
    for (var i = 0, l = available_collections.length; i < l; i++) {
        collections[available_collections[i].dataset.collection] = available_collections[i];
    }

    var available_controls = document.querySelectorAll('[data-controls="collection"]');
    for (var j = 0, jl = available_controls.length; j < jl; j++) {
        var control = available_controls[j];
        // Check if collection is available
        if (collections[control.dataset.collection] === undefined || collections[control.dataset.collection] === null) {
            console.error("No collection field with the name " + control.dataset.collection + " was registered");
            continue;
        }

        attach(control, collections);
    }


});

function attach(control, collections) {
    switch (control.dataset.action) {
        case "add-item":
            control.addEventListener('click', function (e) {
                e.preventDefault();
                var collection = collections[e.currentTarget.dataset.collection];

                // Create a new item-container
                var container = document.createElement('div');
                container.innerHTML = collection.dataset.collectionItemContainer.trim();

                // Fetch the template
                var counter = collection.dataset.collectionItemCounter | collection.children.length;
                var template = document.createElement('div');
                template.innerHTML = collection.dataset.collectionItemPrototype.replace(/__name__/g, counter).trim();

                // Check if we need to add other controls
                if (collection.dataset.collectionItemControls !== undefined && 0 < collection.dataset.collectionItemControls.trim().length) {
                    var controls = document.createElement('div');
                    controls.innerHTML = collection.dataset.collectionItemControls.trim();
                    container.firstChild.appendChild(controls.firstChild);

                    // Now attach to the collection
                    var subcontrols = container.querySelectorAll('[data-controls="collection"]');
                    for (var i = 0, l = subcontrols.length; i < l; i++) {
                        attach(subcontrols[i], collections);
                    }
                }

                container.firstChild.appendChild(template.firstChild);
                collection.appendChild(container.firstChild);

                // Update the item-counter so we have a unique number every time
                collection.dataset.collectionItemCounter = "" + ++counter;
            });
            break;

        case "remove-item":
            // Find containing element using the container tag on the collection
            control.addEventListener('click', function (e) {
                e.preventDefault();
                var collection = collections[e.currentTarget.dataset.collection];
                var container = document.createElement('div');
                container.innerHTML = collection.dataset.collectionItemContainer.trim();
                console.log(container.firstChild);
                var classes = container.firstChild.className.trim().split(' ').join(' .');
                var item = e.currentTarget.parentNode;
                while (item.className.trim().split(' ').join(' .') !== classes) {
                    if (item.tagName === "body") {
                        console.error("Parent not found");
                        return;
                    }
                    item = item.parentNode;
                }
                item.remove();

                // We're not updating any counter here; the numbers aren't used for anything important other than enabling
                // multiple input fields on the same input-name, we validate server side anyway.
            });
            break;
    }
}