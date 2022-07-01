<?php

namespace Spatie\SqlCommenter;

use Illuminate\Database\Connection;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\SqlCommenter\Tests\TestClasses\CustomCommenter;

class SqlCommenterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-sql-commenter')
            ->hasConfigFile();
    }

    public function packageBooted(): void
    {
        app('db.connection')->beforeExecuting(function (string &$query, array &$bindings, Connection $connection) {
            $commenterClass = config('sql-commenter.commenter_class') ?? SqlCommenter::class;

            $commenters = config('sql-commenter.commenters');

            $query = $commenterClass::commentQuery($query, $connection, $commenters);
        });
    }
}
