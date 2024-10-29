<?php

/**
 * Description of AbstractClient
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

abstract class AbstractClient {
    protected function handleRequestResult($request): array
    {
        if (is_wp_error($request)) {
            return ResultBuilder::buildError($request->get_error_message());
        }

        if (intval($request['response']['code']) !== 200) {
            $message = $request['response']['code'];
            if (!empty($request['response']['message'])) {
                $message .= ' - ' . $request['response']['message'];
            }

            return ResultBuilder::buildError(
                $message,
                ['error_code' => $request['response']['code']]
            );
        }

        $result = json_decode($request['body'], true);

        if ($result['state'] === 'error') {
            return ResultBuilder::buildError($result['message']);
        }

        return $result;
    }
}
