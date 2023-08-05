jQuery(document).ready(function($) {
    // Embed the PDF viewer using PDF.js
    const pdfViewer = document.getElementById('pdf-viewer');
    const pdfUrl = 'https://example.com/path/to/your/editable-pdf.pdf'; // Replace this with the URL of your editable PDF
    const pdfjsLib = window['pdfjs-dist/build/pdf'];
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

    pdfjsLib.getDocument(pdfUrl).promise.then(function(pdfDoc_) {
        const pdfDoc = pdfDoc_;
        const numPages = pdfDoc.numPages;
        let pdfData = '';

        function renderPage(pageNumber) {
            pdfDoc.getPage(pageNumber).then(function(page) {
                const scale = 1.5;
                const viewport = page.getViewport({ scale });
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                page.render(renderContext).promise.then(function() {
                    pdfData += 'Page ' + pageNumber + ':\n'; // Add page number to the editable data
                    pdfData += '=======================\n\n'; // Add a separator
                    pdfData += 'User Editable Data from Page ' + pageNumber + ':\n'; // Add a label for user data

                    // Customize this part to capture user input from the editable fields in the PDF
                    // For example, if you have a text input with id "name" in the PDF, you can retrieve it like this:
                    // const nameField = document.getElementById('name');
                    // pdfData += 'Name: ' + nameField.value + '\n';

                    // Similarly, capture other editable fields from the PDF

                    pdfData += '\n\n'; // Add line breaks between pages
                });

                if (pageNumber < numPages) {
                    pageNumber++;
                    renderPage(pageNumber);
                } else {
                    // Attach the collected PDF data to a hidden field in the form
                    const pdfDataField = document.getElementById('editable_pdf_data');
                    pdfDataField.value = pdfData;
                }
            });
        }

        renderPage(1);
    });
});
