exception 'InvalidArgumentException' with message 'Route [twilio_outbound_call_url] not defined.'
in /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Routing/UrlGenerator.php:304
Stack trace:
#0 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Foundation/helpers.php(709): Illuminate\Routing\UrlGenerator->route('twilio_outbound...', Array, true)
#1 /Applications/MAMP/htdocs/TrainingRite/app/Jobs/InitiateOutboundCalls.php(53): route('twilio_outbound...')
#2 [internal function]: App\Jobs\InitiateOutboundCalls->handle()
#3 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(30): call_user_func_array(Array, Array)
#4 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\Container\BoundMethod::Illuminate\Container\{closure}()
#5 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\Container\BoundMethod::callBoundMethod(Object(Illuminate\Foundation\Application), Array, Object(Closure))
#6 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Container/Container.php(539): Illuminate\Container\BoundMethod::call(Object(Illuminate\Foundation\Application), Array, Array, NULL)
#7 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(94): Illuminate\Container\Container->call(Array)
#8 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(114): Illuminate\Bus\Dispatcher->Illuminate\Bus\{closure}(Object(App\Jobs\InitiateOutboundCalls))
#9 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(102): Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}(Object(App\Jobs\InitiateOutboundCalls))
#10 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(98): Illuminate\Pipeline\Pipeline->then(Object(Closure))
#11 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(43): Illuminate\Bus\Dispatcher->dispatchNow(Object(App\Jobs\InitiateOutboundCalls), NULL)
#12 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(69): Illuminate\Queue\CallQueuedHandler->call(Object(Illuminate\Queue\Jobs\DatabaseJob), Array)
#13 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(317): Illuminate\Queue\Jobs\Job->fire()
#14 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(267): Illuminate\Queue\Worker->process('database', Object(Illuminate\Queue\Jobs\DatabaseJob), Object(Illuminate\Queue\WorkerOptions))
#15 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(224): Illuminate\Queue\Worker->runJob(Object(Illuminate\Queue\Jobs\DatabaseJob), 'database', Object(Illuminate\Queue\WorkerOptions))
#16 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(102): Illuminate\Queue\Worker->runNextJob('database', 'outbound_calls', Object(Illuminate\Queue\WorkerOptions))
#17 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(86): Illuminate\Queue\Console\WorkCommand->runWorker('database', 'outbound_calls')
#18 [internal function]: Illuminate\Queue\Console\WorkCommand->fire()
#19 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(30): call_user_func_array(Array, Array)
#20 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(87): Illuminate\Container\BoundMethod::Illuminate\Container\{closure}()
#21 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(31): Illuminate\Container\BoundMethod::callBoundMethod(Object(Illuminate\Foundation\Application), Array, Object(Closure))
#22 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Container/Container.php(539): Illuminate\Container\BoundMethod::call(Object(Illuminate\Foundation\Application), Array, Array, NULL)
#23 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Console/Command.php(182): Illuminate\Container\Container->call(Array)
#24 /Applications/MAMP/htdocs/TrainingRite/vendor/symfony/console/Command/Command.php(264): Illuminate\Console\Command->execute(Object(Symfony\Component\Console\Input\ArgvInput), Object(Illuminate\Console\OutputStyle))
#25 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Console/Command.php(168): Symfony\Component\Console\Command\Command->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Illuminate\Console\OutputStyle))
#26 /Applications/MAMP/htdocs/TrainingRite/vendor/symfony/console/Application.php(874): Illuminate\Console\Command->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#27 /Applications/MAMP/htdocs/TrainingRite/vendor/symfony/console/Application.php(228): Symfony\Component\Console\Application->doRunCommand(Object(Illuminate\Queue\Console\WorkCommand), Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#28 /Applications/MAMP/htdocs/TrainingRite/vendor/symfony/console/Application.php(130): Symfony\Component\Console\Application->doRun(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#29 /Applications/MAMP/htdocs/TrainingRite/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(122): Symfony\Component\Console\Application->run(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#30 /Applications/MAMP/htdocs/TrainingRite/artisan(36): Illuminate\Foundation\Console\Kernel->handle(Object(Symfony\Component\Console\Input\ArgvInput), Object(Symfony\Component\Console\Output\ConsoleOutput))
#31 {main}