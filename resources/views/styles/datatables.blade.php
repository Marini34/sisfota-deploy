<link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<style>
    /* show entries */
    #myTable_length>label>select {
        width: 60px;
        /* Atur lebar sesuai kebutuhan */
        border-radius: 5px;
    }

    .dataTables_length {
        margin-bottom: 10px;
    }

    #myTable_paginate>span>a {
        border-radius: 5px;
        /* Tambahkan sudut bulat pada tombol */
        padding: 0;
        margin-right: 3px;
        margin-left: 3px;
        margin-top: 4px;
    }

    #myTable_previous,
    #myTable_next {
        padding: 0px 0px;
        border: 1px solid rgb(154, 147, 147);
        border-radius: 5px;
    }

    #myTable_previous {
        margin-right: 2px;
    }

    #myTable_next:hover {
        background-color: #ddd;
        /* Warna latar belakang tombol saat dihover */
    }

    #myTable_paginate>span>span {
        padding: 2px;
    }


    /* #myTable > thead > tr {
        border: 2px solid red;
        border-radius: 20px;
    } */

    .pertama {
        border-radius: 8px 0px 0px 0px;
    }

    .terakhir {
        border-radius: 0px 8px 0px 0px;
    }
</style>
