<style>
    p {
        margin: 20px 0;
    }

    /*
    Generic Styling, for Desktops/Laptops
    */
    table {
        width: 100%;
        border-collapse: collapse;
    }
    /* Zebra striping */
    tr:nth-of-type(odd) {
        background: #eee;
    }
    th {
        background: #333;
        color: white;
        font-weight: bold;
    }
    td, th {
        padding: 6px;
        border: 1px solid #ccc;
        text-align: left;
    }

    @media
    only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px)  {
        td:nth-of-type(1):before { content: "<?= __('Trader instrument') ?>"; }
        td:nth-of-type(2):before { content: "<?= __('Current price') ?>"; }
        td:nth-of-type(3):before { content: "<?= __('High') ?>"; }
        td:nth-of-type(4):before { content: "<?= __('Low') ?>"; }
        td:nth-of-type(5):before { content: "<?= __('Gain/Loss'); ?>"; }
    }
    @media
    only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px)  {

        /* Force table to not be like tables anymore */
        table, thead, tbody, th, td, tr {
            display: block;
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        tr { border: 1px solid #ccc; }

        td {
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
        }

        td:before {
            /* Now like a table header */
            position: absolute;
            /* Top/left values mimic padding */
            top: 6px;
            left: 6px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
        }
    }

    /* Smartphones (portrait and landscape) ----------- */
    @media only screen
    and (min-device-width : 320px)
    and (max-device-width : 480px) {
        body {
            padding: 0;
            margin: 0;
            width: 100%; }

    }

    /* iPads (portrait and landscape) ----------- */
    @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
        body {
            width: 100%;
        }
        #page-wrap {
            margin: 50px;
        }
    }
    @media only screen and (max-width: 350px) {
        tr{
            font-size: 11px !important;
        }
        tr .font-size{
            font-size: 11px !important;
        }
    }
    @media only screen and (max-width: 300px) {
        tr{
            font-size: 10px !important;
        }
        tr .font-size{
            font-size: 10px !important;
        }
    }

</style>
