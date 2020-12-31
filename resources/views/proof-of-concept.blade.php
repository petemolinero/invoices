<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Invoices</title>

        <style>
            body {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                width: 100vw;
                padding: 0;
                margin: 0;
            }

            input {
                display: block;
                margin-bottom: 10px;
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.5.207/build/pdf.min.js"></script>
    </head>

    <body>
        <input type="text" id="name">
        <input type="text" id="company">

        <input type="file" id="pdf">
        <a href="/download" id="download-link">Download PDF</a>


        <script>
            var nameInput = document.getElementById('name');
            var companyInput = document.getElementById('company');
            var pdfInput = document.getElementById('pdf');
            var downloadLink = document.getElementById('download-link');

            function updateUrl() {
                var name = nameInput.value;
                var company = companyInput.value;
                url = encodeURI('/download?name=' + name + '&company=' + company); 
                downloadLink.href = url;
            }

            document.getElementById('name').onkeyup = function(event) {
                updateUrl();
            };

            document.getElementById('company').onkeyup = function(event) {
                updateUrl();
            };


            //Step 1: Get the file from the input element                
            pdfInput.onchange = function(event) {

                var file = event.target.files[0];

                //Step 2: Read the file using file reader
                var fileReader = new FileReader();  

                fileReader.onload = function() {

                    //Step 4:turn array buffer into typed array
                    var typedarray = new Uint8Array(this.result);

                    //Step 5:PDFJS should be able to read this
                    pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                        pdf.getMetadata().then(function(meta) {
                            data = JSON.parse(meta.info.Producer);
                            nameInput.value = data.name;
                            companyInput.value = data.company;
                            console.log(meta); // Metadata object here
                        }).catch(function(err) {
                           console.log('Error getting meta data');
                           console.log(err);
                        });
                    });

                };
                //Step 3:Read the file as ArrayBuffer
                fileReader.readAsArrayBuffer(file);
            }
        </script>
    </body>
</html>
