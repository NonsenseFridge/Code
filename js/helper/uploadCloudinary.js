function uploadCloudinary(bitmap, callbak) {
    var p = this;
    var cloud_name = 'djobjzocx';
    var preset_name = 'nonsensefridge';
    var mainFolder = 'nonsensefridge';

    // var sendImg = new Bitmap(objForBitmap.bitmap);
    var dataToLoad = bitmap.cache().cacheCanvas.toDataURL();
    // var name_file = app.gameData.playerName//new Date().getTime() + "_" + Math.floor(Math.random() * 100);
    // var dataToLoad = objForBitmap.bitmap.image.src;

    var cloud_image_uploader = $('#url').unsigned_cloudinary_upload(preset_name,
        {
            cloud_name: cloud_name,
            tags: 'browser_uploads',
            folder: mainFolder
        }
    ).cloudinary_upload_url(dataToLoad)
        .on('cloudinarydone', function (e, data) {
            cloud_image_uploader.off('cloudinarydone');
            callbak(data.result.secure_url);
            zog(e, data)
        }).on('cloudinaryprogress', function (e, data) {
            // zog(e, data)
        }).on('cloudinaryerror', function (e, data) {
            zog(e, data)
        });
}