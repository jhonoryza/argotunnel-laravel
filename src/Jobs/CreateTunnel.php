<?php

namespace Jhonoryza\ArgoTunnel\Jobs;

use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTunnel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Command for checking if the tunnel is open
     * @var string
     */
    protected $ncCommand;

    /**
     * The command for creating the tunnel
     * @var string
     */
    protected $sshCommand;

    /**
     * The command for creating the tunnel
     * @var string
     */
    protected $bashCommand;

    /**
     * Simple place to keep all output.
     * @var array
     */
    protected $output = [];

    public function __construct()
    {

        $this->ncCommand = sprintf('%s -vz %s %d  > /dev/null 2>&1',
            config('tunneler.nc_path'),
            config('tunneler.local_address'),
            config('tunneler.local_port')
        );

        $this->bashCommand = sprintf('timeout 1 %s -c \'cat < /dev/null > /dev/tcp/%s/%d\' > /dev/null 2>&1',
            config('tunneler.bash_path'),
            config('tunneler.local_address'),
            config('tunneler.local_port')
        );

        $this->sshCommand = sprintf("%s access ssh --hostname dbmysql.labkita.my.id --url localhost:4000",
            config('tunneler.ssh_path')
        );

    }


    public function handle(): int
    {
        if ($this->verifyTunnel()) {
            return 1;
        }

        $this->createTunnel();

        $tries = config('tunneler.tries');
        for ($i = 0; $i < $tries; $i++) {
            if ($this->verifyTunnel()) {
                return 2;
            }

            // Wait a bit until next iteration
            usleep(config('tunneler.wait'));
        }

        throw new \ErrorException(sprintf("Could Not Create SSH Tunnel with command:\n\t%s\nCheck your configuration.",
            $this->sshCommand));
    }


    /**
     * Creates the SSH Tunnel for us.
     */
    protected function createTunnel()
    {
        $this->runCommand(sprintf('%s %s >> %s 2>&1 &',
            config('tunneler.nohup_path'),
            $this->sshCommand,
            config('tunneler.nohup_log')
        ));
        // Ensure we wait long enough for it to actually connect.
        usleep(config('tunneler.wait'));
    }

    /**
     * Verifies whether the tunnel is active or not.
     * @return bool
     */
    protected function verifyTunnel()
    {
        if (config('tunneler.verify_process') == 'bash') {
            return $this->runCommand($this->bashCommand);
        }

        return $this->runCommand($this->ncCommand);
    }

    /*
     * Use pkill to kill the SSH tunnel
     */

    public function destoryTunnel(){
        $ssh_command = preg_replace('/[\s]{2}[\s]*/',' ',$this->sshCommand);
        return $this->runCommand('pkill -f "'.$ssh_command.'"');
    }

    /**
     * Runs a command and converts the exit code to a boolean
     * @param $command
     * @return bool
     */
    protected function runCommand($command)
    {
        $return_var = 1;
        exec($command, $this->output, $return_var);
        return (bool)($return_var === 0);
    }
}