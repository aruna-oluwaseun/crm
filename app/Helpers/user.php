<?php

/**
 * Get current user
 * basically same as is_logged_in
 * @TODO modify so can be used with shops, currently uses Auth Guard
 */
if(!function_exists('get_user'))
{
    function get_user( $user_id = false )
    {
        // return  the logged in user
        if($user_id) {
            return is_logged_in();
        } else {
            return is_logged_in();
        }

        return false;
    }
}

/**
 * Is the user logged in
 * @TODO modify so can be used with shops, currently uses Auth Guard
 */
if(!function_exists('is_logged_in'))
{
    function is_logged_in()
    {
        if($user = \Illuminate\Support\Facades\Auth::user())
        {
            return $user;
        }

        return false;
    }
}

if(!function_exists('current_user_id'))
{
    function current_user_id()
    {
        $user = is_logged_in();

        return $user ? $user->id : false;
    }
}

if(!function_exists('current_user_franchise_id'))
{
    function current_user_franchise_id()
    {
        $user = is_logged_in();

        return $user ? $user->franchise_id : false;
    }
}

if(!function_exists('current_user_franchise'))
{
    function current_user_franchise()
    {
        $franchise_id = current_user_franchise_id();

        return \App\Models\Franchise::find($franchise_id);
    }
}

/**
 * Has access to all franchise data for everyone
 */
if(!function_exists('franchise_global_owner'))
{
    function franchise_global_owner()
    {
        $user = is_logged_in();

        if($user) {
            return $user->franchise->global_owner;
        }

        return false;
    }
}

/**
 * Get list of staff users
 */
if(!function_exists('get_users'))
{
    function get_users()
    {
        $users = \App\Models\User::active()->get();

        if( $users && $users->count() ) {
            return $users;
        }

        return false;
    }
}

if(!function_exists('remaining_holiday'))
{
    function remaining_holiday($user)
    {
        return \App\Models\User::remainingHolidays($user);
    }
}

