# laravel-request-queue
Request Queues For Api's For Laravel

## How To use it
Add in your page like;

  ### We added request to queue
  
        $this->connect('MAIN_KEY');
        $this->transId = 'REQUEST_UNIQUE_ID';
        $this->set($this->transId);
        $this->waitOn();
        
  ### When the request has finished process, delete it from queue

        $this->remove($this->transId);


  ### For Example
