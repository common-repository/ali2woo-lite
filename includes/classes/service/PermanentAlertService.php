<?php

/**
 * Description of PermanentAlert
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class PermanentAlertService
{
    protected BackgroundProcessService $BackgroundProcessService;

    public function __construct(BackgroundProcessService $BackgroundProcessService) {
        $this->BackgroundProcessService = $BackgroundProcessService;
    }

    /**
     * @return array|PermanentAlert[]
     */
    public function getAll(): array
    {
        $PermanentAlerts = [];
        
        foreach ($this->BackgroundProcessService->getAll() as $BackgroundProcess) {
            if ($BackgroundProcess->isQueued()) {
                $count = $BackgroundProcess->getSize();
                $helpButtonText = esc_html__('Cancel it?', 'ali2woo');
                $helpButtonHtml = sprintf(
                /* translators: %s is replaced with a process name */
                    '<a class="cancel-process" data-process="%s" href="#">%s</a>',
                    $BackgroundProcess->getName(),
                    $helpButtonText
                );

                $content = sprintf(
                    /* translators: %s is replaced with a process name, %d is replaced count of tasks of given process, %s is replaced with cancel button html */
                    'Currently, you have an active and running <strong>%s</strong> process with %d task(s) remaining. %s',
                    $BackgroundProcess->getTitle(),
                    $count,
                    $helpButtonHtml
                );

                $PermanentAlerts[] = new PermanentAlert($content, PermanentAlert::TYPE_INFO);
            }
        }

        return $PermanentAlerts;
    }
}
