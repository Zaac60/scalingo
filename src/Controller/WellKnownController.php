<?php

/**
 * This file is part of the GoGoCarto project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2016 Sebastian Castro - 90scastro@gmail.com
 * @license    MIT License
 * @Last Modified time: 2018-07-08 12:11:20
 */

namespace App\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WellKnownController extends GoGoController
{
    public function assetLinksAction(Request $request, DocumentManager $dm)
    {
        $config = $dm->getRepository('App\Document\Configuration')->findConfiguration();

        if( !$config->getPackageName() || !$config->getSha256CertFingerprints() ) {
            throw new NotFoundHttpException();
        }

        // Attention: the double [] is required, as this is an array inside an array
        $assetLinks = [[
            "relation" => [ "delegate_permission/common.handle_all_urls" ],
            "target" => [
                "namespace" => "android_app",
                "package_name" => $config->getPackageName(),
                "sha256_cert_fingerprints" => [ $config->getSha256CertFingerprints() ]
            ]
        ]];

        return new JsonResponse($assetLinks, Response::HTTP_OK, ['Access-Control-Allow-Origin: *']);
    }
}
