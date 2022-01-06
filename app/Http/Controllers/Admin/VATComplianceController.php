<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Repositories\HMRC\Environment\Environment;
use App\Repositories\HMRC\Oauth2\AccessToken;
use App\Repositories\HMRC\ServerToken\ServerToken;
use App\Repositories\HMRC\VAT\RetrieveVATObligationsRequest;
use Illuminate\Http\Request;
use App\Repositories\HMRC\Oauth2\Provider;
use App\Repositories\HMRC\Scope\Scope;
use Illuminate\Support\Facades\Auth;

class VATComplianceController extends Controller
{

    // HMRC Creds
    private $clientId;
    private $clientSecret;

    public function __construct()
    {
        $this->clientId = env('HMRC_CLIENT');
        $this->clientSecret = env('HMRC_SECRET');
    }

    /**
     * Check if vat testing is enabled
     */
    protected function checkVatTesting()
    {
        $environment = Environment::getInstance();

        if($environment->isSandbox())
        {
            $this->clientId = env('TEST_HMRC_CLIENT');
            $this->clientSecret = env('TEST_HMRC_SECRET');
        }
    }

    public function setVatTesting($value)
    {
        $environment = Environment::getInstance();

        if($value == 0)
        {
            $environment->setToLive();
            return back()->with('success','Testing HMRC Vat disabled');
        }
        else
        {
            $environment->setToSandbox();
            return back()->with('success','Testing HMRC Vat now enabled');
        }
    }

    /**
     * The vat settings
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function settings()
    {
        $franchise = Franchise::findOrFail(current_user_franchise_id());

        set_page_title('VAT');
        return view('admin.VAT.vat-settings',compact(['franchise']));
    }

    /**
     * Show vat tools
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $franchise = Franchise::findOrFail(current_user_franchise_id());

        set_page_title('VAT');
        return view('admin.VAT.vat-dashboard', compact(['franchise']));
    }


    /**
     * Create an access token
     * @param Request $request
     * @param $franchise_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAccessToken(Request $request, $franchise_id)
    {
        $franchise = Franchise::findOrFail($franchise_id);
        session()->put('franchise_id', $franchise_id);

        // check testing
        $this->checkVatTesting();

        $provider = new Provider(
            $this->clientId,
            $this->clientSecret,
            url('vat/handle-response')
        );
        $scope = [Scope::VAT_READ, Scope::HELLO, Scope::VAT_WRITE];

        return $provider->redirectToAuthorizationURL($scope);
    }

    /**
     * Handle HMRC response
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \App\Repositories\HMRC\Exceptions\InvalidVariableTypeException
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function handleResponse(Request $request)
    {
        // check testing
        $this->checkVatTesting();

        try {
            $provider = new Provider(
                $this->clientId,
                $this->clientSecret,
                url('vat/handle-response')
            );

            // Try to get an access token using the authorization code grant.
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $request->input('code'),
            ]);

            if(! $franchise = Franchise::find(session()->get('franchise_id')) )
            {
                return redirect('vat/settings')->with('error','Could not locate account who requested gov authorisation');
            }

            AccessToken::set($accessToken, $franchise);
            return redirect('vat')->with('success','Access granted to HMRC');
        }
        catch (\Throwable $exception)
        {
            custom_reporter($exception);
        }

        return redirect('vat/settings')->with('error','Access token found but failed to save');
    }

    // -------------
    public function test()
    {
        set_page_title('VAT Compliance');
        return view('admin.VAT.vat-test');
    }

    public function helloWorld()
    {
        $request = new \App\Repositories\HMRC\Hello\HelloWorldRequest();
        $response = $request->fire();
        $response->echoBodyWithJsonHeader();
    }

    public function helloWorldApplication(Request $request)
    {
        ServerToken::getInstance()->set($request->input('server_token'));

        $request = new \App\Repositories\HMRC\Hello\HelloApplicationRequest();
        $response = $request->fire();
        $response->echoBodyWithJsonHeader();
    }

    public function helloWorldUser()
    {
        $request = new \App\Repositories\HMRC\Hello\HelloUserRequest();
        $response = $request->fire();
        $response->echoBodyWithJsonHeader();
    }
}
