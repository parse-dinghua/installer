<?php namespace Orchestra\Installation\Processor;

use ReflectionException;
use Orchestra\Model\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Orchestra\Contracts\Installation\Requirement;
use Orchestra\Contracts\Installation\Installation;

class Installer
{
    /**
     * Installer instance.
     *
     * @var \Orchestra\Contracts\Installation\Installation
     */
    protected $installer;

    /**
     * Requirement instance.
     *
     * @var \Orchestra\Contracts\Installation\Requirement
     */
    protected $requirement;

    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Contracts\Installation\Installation  $installer
     * @param  \Orchestra\Contracts\Installation\Requirement  $requirement
     */
    public function __construct(Installation $installer, Requirement $requirement)
    {
        $this->installer   = $installer;
        $this->requirement = $requirement;

        $this->installer->bootInstallerFiles();
    }

    /**
     * Start an installation and check for requirement.
     *
     * @param  object  $listener
     * @return mixed
     */
    public function index($listener)
    {
        $requirement = $this->requirement;
        $installable = $requirement->check();

        list($database, $auth, $authentication) = $this->getRunningConfiguration();

        // If the auth status is false, installation shouldn't be possible.
        (true === $authentication) || $installable = false;

        $data = [
            'database'       => $database,
            'auth'           => $auth,
            'authentication' => $authentication,
            'installable'    => $installable,
            'checklist'      => $requirement->getChecklist(),
        ];

        return $listener->indexSucceed($data);
    }

    /**
     * Run migration and prepare the database.
     *
     * @param  object  $listener
     * @return mixed
     */
    public function prepare($listener)
    {
        $this->installer->migrate();

        return $listener->prepareSucceed();
    }

    /**
     * Display initial user and site configuration page.
     *
     * @param  object  $listener
     * @return mixed
     */
    public function create($listener)
    {
        return $listener->createSucceed([
            'siteName' => 'Orchestra Platform',
        ]);
    }

    /**
     * Store/save administator information and site configuration
     *
     * @param  object  $listener
     * @param  array   $input
     * @return mixed
     */
    public function store($listener, array $input)
    {
        if (! $this->installer->createAdmin($input)) {
            return $listener->storeFailed();
        }

        return $listener->storeSucceed();
    }

    /**
     * Complete the installation.
     *
     * @param  object  $listener
     * @return mixed
     */
    public function done($listener)
    {
        return $listener->doneSucceed();
    }

    /**
     * Get running configuration.
     *
     * @return array
     */
    protected function getRunningConfiguration()
    {
        $driver   = Config::get('database.default', 'mysql');
        $database = Config::get("database.connections.{$driver}", []);
        $auth     = Config::get('auth');

        // For security, we shouldn't expose database connection to anyone,
        // This snippet change the password value into *.
        if (isset($database['password']) && ($password = strlen($database['password']))) {
            $database['password'] = str_repeat('*', $password);
        }

        $authentication = $this->isAuthenticationInstallable($auth);

        return [$database, $auth, $authentication];
    }

    /**
     * Is authentication installable.
     *
     * @param  array    $auth
     * @return bool
     */
    protected function isAuthenticationInstallable($auth)
    {
        // Orchestra Platform strictly require Eloquent based authentication
        // because our Role Based Access Role (RBAC) is utilizing on eloquent
        // relationship to solve some of the requirement.
        try {
            $eloquent = App::make($auth['model']);

            return ($auth['driver'] === 'eloquent' && $eloquent instanceof User);
        } catch (ReflectionException $e) {
            return false;
        }
    }
}