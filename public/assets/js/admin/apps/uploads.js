// Test dropzone with vapor
var accepted_files = $('.upload-zone').data('accepted-files') ? $('.upload-zone').data('accepted-files') : null;
const dropzoneForm = new Dropzone(".upload-zone", {acceptedFiles: accepted_files});
Dropzone.prototype.uploadFiles = async files => files.forEach(uploadFile);

dropzoneForm.on('success', function(file, response) {
    if(response.success)
    {
        /**
         * @TODO implement this so we dont need to refresh
         */
        // add file to DOM so we dont use reload
        console.log('File uploaded');
    }
});
dropzoneForm.on('queuecomplete', function(response) {
    console.log('queue complete debug')
    debug(response);
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Files uploaded. Please wait while we re-load this page.',
        //footer: '<a href>Why do I have this issue?</a>'
    });

    /**
     * @TODO remove when we implement the success event
     */
    /*setTimeout(function() {
        location.reload();
    }, 2000)*/

});
dropzoneForm.on('error', function (file,error, XHR){
    if (!file.accepted) {
        this.removeFile(file);
        Swal.fire({
            icon: 'error',
            title: 'File type not accepted',
            text: 'You have tried adding a file type that is not accepted.',
        })
    }
    if(XHR.status === 419)
    {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Token expired, please refresh and try again.',
        });
    }
    else
    {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error,
        });
    }
});

async function uploadFile(file) {
    debug(file);

    const s3response = await Vapor.store(file, {
        progress: progress => {
            const percentage = Math.round(progress * 100 * 0.9);
            dropzoneForm.emit("uploadprogress", file, percentage);
        }
    });

    const itemResponse = await axios.post(dropzoneForm.element.action, {
        filename: file.name,
        filetype: file.type,
        tmp: s3response.key
    });

    file.status = Dropzone.SUCCESS;

    dropzoneForm.emit("uploadprogress", file, 100);
    dropzoneForm.emit("complete", file);
    dropzoneForm.processQueue();
}
