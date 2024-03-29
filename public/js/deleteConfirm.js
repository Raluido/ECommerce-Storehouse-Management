$(window).on('load', function () {
    $('.deleteProduct').on('click', function () {
        if (confirm("Estas seguro de eliminar la ficha de producto? Si lo haces eliminarás las unidades en el almacén.")) {
            $.ajax({
                url: '/backoffice/products/' + $(this).val() + '/delete',
                type: 'get',
                data: {},
                success: function () {
                    location.reload();
                }
            })
        }

    })

    $('.deleteCategory').on('click', function () {
        if (confirm("Estas seguro de eliminar la categoría? Si lo haces eliminarás las unidades de producto y la ficha del producto asociado.")) {
            $.ajax({
                url: '/backoffice/categories/' + $(this).val() + '/delete',
                type: 'get',
                data: {},
                success: function () {
                    location.reload();
                }
            })
        }

    })

    $('.deleteStorehouse').on('click', function () {
        if (confirm("Estas seguro de eliminar el almacén? Si lo haces eliminarás las unidades de producto que contenga.")) {
            $.ajax({
                url: '/backoffice/storehouses/' + $(this).val() + '/delete',
                type: 'get',
                data: {},
                success: function () {
                    location.reload();
                }
            })
        }

    })
})