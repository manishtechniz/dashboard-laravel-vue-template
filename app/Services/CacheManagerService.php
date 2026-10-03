<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;

class CacheManagerService
{
    /**
     * Available clear actions, each running one command or a list of them in order.
     */
    protected array $clearActions = [
        'clear-all' => ['optimize:clear'],
        'clear-config' => 'config:clear',
        'clear-cache' => 'cache:clear',
        'clear-compiled' => 'clear-compiled',
        'clear-events' => 'event:clear',
        'clear-routes' => 'route:clear',
        'clear-views' => 'view:clear',
    ];

    /**
     * Available build actions.
     */
    protected array $buildActions = [
        'build-all' => 'optimize',
        'build-config' => 'config:cache',
        'build-routes' => 'route:cache',
        'build-views' => 'view:cache',
    ];

    /**
     * Execute a cache action by key.
     */
    public function execute(string $action): array
    {
        $allActions = array_merge($this->clearActions, $this->buildActions);

        if (! isset($allActions[$action])) {
            return [
                'success' => false,
                'message' => 'Invalid cache action requested.',
                'output' => '',
                'command' => '',
            ];
        }

        $commands = (array) $allActions[$action];

        $command = implode(' && php artisan ', $commands);

        try {
            $output = [];

            foreach ($commands as $each) {
                $exitCode = Artisan::call($each);

                $output[] = trim(Artisan::output());

                if ($exitCode !== 0) {
                    return [
                        'success' => false,
                        'message' => $this->message($action, 'failed'),
                        'output' => trim(implode("\n", array_filter($output))),
                        'command' => $command,
                    ];
                }
            }

            return [
                'success' => true,
                'message' => $this->message($action, 'success'),
                'output' => trim(implode("\n", array_filter($output))),
                'command' => $command,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Cache action failed: ' . $e->getMessage(),
                'output' => $e->getMessage(),
                'command' => $command,
            ];
        }
    }

    /**
     * Get clear actions definitions for the view.
     */
    public function getClearActions(): array
    {
        return $this->clearActions;
    }

    /**
     * Get build actions definitions for the view.
     */
    public function getBuildActions(): array
    {
        return $this->buildActions;
    }

    /**
     * What an action answers with: its own sentence when it has one, and otherwise a message
     * naming the action the way the button that ran it does.
     */
    protected function message(string $action, string $outcome)
    {
        $key = [
            'clear-all' => 'All caches have been cleared.',
            'clear-config' => 'The configuration cache has been cleared.',
            'clear-cache' => 'The application cache has been cleared.',
            'clear-compiled' => 'The compiled class files have been removed.',
            'clear-events' => 'The event cache has been cleared.',
            'clear-routes' => 'The route cache has been cleared.',
            'clear-views' => 'The compiled views have been cleared.',
            'clear-page-cache' => 'The page cache has been cleared. Every storefront page will be built again on its next visit.',
            'build-all' => 'All caches have been rebuilt.',
            'build-config' => 'The configuration cache has been built.',
            'build-routes' => 'The route cache has been built.',
            'build-views' => 'The views have been compiled.',
        ][$action] ?? 'Unknown';

        if (
            $outcome === 'success'
            && ($result = trans($key)) !== $key
        ) {
            return $result;
        }

        return [
            'success' => $this->label($action) . ' completed successfully.',
            'failed' => $this->label($action) . ' failed. Check the output below.',
            'exception' => 'Cache action failed'
        ][$outcome] ?? null;
    }

    /**
     * The name the configuration screen gives an action, falling back to its own key.
     */
    protected function label(string $action)
    {
        $key = [
            'clear-all' => 'Clear All Cache',
            'clear-config' => 'Clear Config Cache',
            'clear-cache' => 'Clear Application Cache',
            'clear-compiled' => 'Clear Compiled Cache',
            'clear-events' => 'Clear Event Cache',
            'clear-routes' => 'Clear Route Cache',
            'clear-views' => 'Clear View Cache',
            'clear-page-cache' => 'Clear Page Cache',
            'build-all' => 'Rebuild All Cache',
            'build-config' => 'Cache Config',
            'build-routes' => 'Cache Routes',
            'build-views' => 'Cache Views',
        ][$action] ?? null;

        return ($label = trans($key)) === $key ? $action : $label;
    }
}
