# Request Queue For Laravel
Request Queues For Api's For Laravel

<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>
<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## How to use it
Add in your page like;

  ### Connect to Queue And Set Request ID, It will process in order
  
	$queue = new RequestQueue;
        $queue->connect('MAIN_KEY');
        $this->transId = 'UNIQUE_REQUEST_ID';
        $queue->set($this->transId);
        $queue->waitOn($this->transId);
        
  ### When the request has finished process, delete it from queue

        $queue->remove($this->transId);


  ### For Example

      namespace App\Http\Controllers\ExampleController;

      use App\Http\Library\RequestQueue;


      class ExampleController extends Controller
      {


          private $transId;

          public function __construct(Request $request)
          {

          }

          public function __destruct(){

          }

          public function checkQueue(Request $request)
          {
              # We added request to queue
              $queue = new RequestQueue;
	      $queue->connect('MAIN_KEY');
              $this->transId = 'UNIQUE_REQUEST_ID';
              $queue->set($this->transId);
              $queue->waitOn($this->transId);

              // Here are some processes

              # Process finished
              $queue->remove($this->transId);
          }

      }
