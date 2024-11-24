<?php

declare(strict_types=1);

interface ApiResult{}
class UserProfileResult implements ApiResult{}

/**
 * @template T of ApiResult
 */
abstract class ApiClient
{
    /**
     * @return T
     */
    abstract public function get(): ApiResult;
}

/** @extends ApiClient<UserProfileResult> */
class UserProfileClient extends ApiClient
{
    public function get(): ApiResult
    {
        $result = new UserProfileResult();
        // nanika suru
        return  $result;
    }
}

/**
 * @template T
 */
interface Cacher
{
    /** @return T */
    public function load(): mixed;

}

class Book{}
class Magazine extends Book{}

/** @template T */
interface Criteria{}
/** @extends Criteria<Book> */
interface BookCriteria extends Criteria{}

/**
 * @template T
 */
interface Collection
{
    /**
     * @param Criteria<T> $c
     * @return ?T
     */
    public function matchFirst(Criteria $c): mixed;
}

/** @implements  Collection<Magazine> */
abstract class MagazineCollection implements Collection
{
}
