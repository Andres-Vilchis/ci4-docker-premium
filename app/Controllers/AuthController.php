<?php

namespace App\Controllers;

use App\Services\TenantMembershipService;
use App\Services\TenantSessionService;

class AuthController extends BaseController
{
    public function loginForm()
    {
        return redirect()->to('/login');
    }

    public function registerForm()
    {
        return redirect()->to('/register');
    }

    public function postLoginRedirect()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->to('/login');
        }

        $organizations = TenantMembershipService::getOrganizationsForUser(
            $user->id
        );

        if (empty($organizations)) {
            auth()->logout();

            return redirect()
                ->to('/login')
                ->with(
                    'error',
                    'No organization assigned'
                );
        }

        if (count($organizations) === 1) {

            TenantSessionService::set(
                (int) $organizations[0]['organization_id']
            );

            return redirect()->to('/');
        }

        return view(
            'auth/select-organization',
            [
                'organizations' => $organizations,
            ]
        );
    }

    public function setOrganization($id)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->to('/login');
        }

        $organizationId = (int) $id;

        $allowed =
            TenantMembershipService::userBelongsToOrganization(
                $user->id,
                $organizationId
            );

        if (!$allowed) {
            return redirect()
                ->to('/post-login')
                ->with(
                    'error',
                    'Organization not allowed'
                );
        }

        TenantSessionService::set(
            $organizationId
        );

        return redirect()->to('/');
    }

    public function logout()
    {
        TenantSessionService::clear();

        auth()->logout();

        return redirect()->to('/login');
    }
}