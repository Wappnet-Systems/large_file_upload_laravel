<?php

namespace App\Lib;

use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Sdk;

/**
 * This class with contains the functions related to pre signed s3 urls
 *
 */
class S3PresignedUrlGenerator {

    // this function will generate a put object url
    public function putObjectUrlGenerate($file_name): array {
        try {

            

            // create s3 client object
            $sdk = new Sdk([
                'region' => env('AWS_DEFAULT_REGION'),
                'version' => 'latest'
            ]);
            $client = $sdk->createS3();

            // set expire time, Note: In procution take value from env and config file
            // for security reason make it 1 minutes if possible and generate link only when needed
            $expiry = "+1 minutes";

            // meta data to bind with object link for user or any other reference
            $options = ['user-data' => 'user-meta-value'];

            $cmd = $client->getCommand('PutObject', [
                'Bucket' => \Config::get('filesystems.disks.s3.bucket'),
                'Key' => 'public/presignedurltest/' . $file_name,
                'ACL' => 'public-read', // based on situation change this value or make it dynamic
                'Metadata' => $options,
            ]);

            $request = $client->createPresignedRequest($cmd, $expiry);

            $presignedUrl = (string) $request->getUri();
            return ['status' => true, 'presigned_url' => $presignedUrl];
        } catch (\Exception $exc) {
            dd($exc); // remove this in production
            return ['status' => false];
        }
    }

}
