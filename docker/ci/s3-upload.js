const argv = require('minimist')(process.argv.slice(2));
const AWS = require('aws-sdk');
const fs = require('fs');
const path = require('path');

const config = {
    accessKey: process.env.AWS_ID,
    secreteKey: process.env.AWS_KEY,
    bucket: process.env.S3_BUCKET,
    folderPath: argv.dir,
    prefix: argv.prefix || ''
};

const operation = argv.action || 'add';
const exludes = argv.skip ? argv.skip.split(',') : [];

AWS.config.update({
    accessKeyId: config.accessKey,
    secretAccessKey: config.secreteKey
});
const s3 = new AWS.S3();

// resolve full folder path
const distFolderPath = path.join(process.cwd(), config.folderPath);

function uploadFiles(directory, files, prefix) {

    if(!files || files.length === 0) {
        console.log(`provided folder '${directory}' is empty or does not exist.`);
        console.log('Make sure your project was compiled!');
        return;
    }

    for (const fileName of files) {

        if (exludes.includes(fileName)) {
            continue;
        }

        // get the full path of the file
        const filePath = path.join(directory, fileName);

        // ignore if directory
        if (fs.lstatSync(filePath).isDirectory()) {
            continue;
        }

        // read file contents
        fs.readFile(filePath, (error, fileContent) => {
            // if unable to read file contents, throw exception
            if (error) { throw error; }

            const base64data = new Buffer(fileContent, 'binary');
            const key = prefix + fileName;
            // upload file to S3
            s3.putObject({
                Bucket: config.bucket,
                Key: key,
                Body: base64data,
                ACL: 'public-read'
            }, (res) => {
                console.log(res);
                console.log(`Successfully uploaded '${fileName}' to ${key}!`);
            });

        });
    }
}

function getFiles(directory, prefix) {
    const list = [];
    const files = fs.readdirSync(directory);
    files.forEach(file => {
        const filePath = path.join(directory, file);

        if (fs.lstatSync(filePath).isDirectory()) {
            list.push(...getFiles(filePath, file + '/'));
            return;
        }

        list.push(prefix + file);
    });

    return list;
}

function clearOldFilesS3(bucketName, folder, files){
    const params = {
        Bucket: bucketName,
        Prefix: folder // 'folder/'
    };

    s3.listObjectsV2(params, function(err, listObjects) {
        if (err) throw err;

        if (listObjects.Contents.length === 0) return;

        const deleteParams = {
            Bucket: bucketName,
            Delete: { Objects: [] }
        };

        listObjects.Contents.forEach(function(content) {
            const key = content.Key;           
            if (key.endsWith('/') || files.includes(key)) {
                return;
            }

            deleteParams.Delete.Objects.push({Key: key});
        });

        s3.deleteObjects(deleteParams, function(err, data) {
            if (err) throw err;
            if(listObjects.Contents.IsTruncated) clearOldFilesS3(bucketName, dir, files);
        });
    });
}

const files = getFiles(distFolderPath, '');

switch (operation) {
    case 'add':
        uploadFiles(distFolderPath, files, config.prefix);
        break;
    case 'del':
        // clear old files in folder bucket
        const keyFiles = files.map((file) => config.prefix + file);
        clearOldFilesS3(config.bucket, config.prefix, keyFiles);
        break;
}

console.log('S3 COMPLETED!!');