# laravel-request-queue
Request Queues For Api's For Laravel

<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>
<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## How To use it
Add in your page like;

  ### We added request to queue
  
        $this->connect('MAIN_KEY');
        $this->transId = 'UNIQUE_REQUEST_ID';
        $this->set($this->transId);
        $this->waitOn();
        
  ### When the request has finished process, delete it from queue

        $this->remove($this->transId);


  ### For Example

      namespace App\Http\Controllers\ExampleController;

      use App\Http\Traits\RequestQueueTrait;


      class ExampleController extends Controller
      {


          use RequestQueueTrait;

          private $transId;

          public function __construct(Request $request)
          {

          }

          public function __destruct(){
              if($this->transId != '') {
                  $this->remove($this->transId);
              }
          }

          public function checkQueue(Request $request)
          {
              # We added request to queue
              $this->connect('MAIN_KEY');
              $this->transId = 'UNIQUE_REQUEST_ID';
              $this->set($this->transId);
              $this->waitOn();

              // Here are some processes

              # Process finished
              $this->remove($this->transId);
          }

      }
