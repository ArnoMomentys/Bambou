<?php

/**
 * EventOptions Model
 * CRUD the EventOptions table
 */
class EventOptions extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db, 'eventoptions');
    }


 	/**
 	 * Add event options from post datas
 	 */
    public function add() {
        // $this->copyFrom('POST');
        $newData = [];
        $this->copyFrom('POST', function($data) {
            foreach ($data as $key => $value) {
                $newData[$key] = Controller::sanitizeDatas($value);
            }
            return $newData;
        });
		if($this->showContact) $this->showContact = $this->showContact == "on" ? 1 : 0;
		if($this->withSms) $this->withSms = $this->withSms == "on" ? 1 : 0;
		if($this->withRepr) $this->withRepr = $this->withRepr == "on" ? 1 : 0;
		if($this->withAcc) $this->withAcc = $this->withAcc == "on" ? 1 : 0;
		if($this->sendType) $this->sendType = $this->sendType == "on" ? 1 : 0;
		if($this->sendType) $this->sendType = $this->sendType == "on" ? 1 : 0;
        if($this->withBusinessCard) $this->withBusinessCard = $this->withBusinessCard == "on" ? 1 : 0;
        if($this->cardAddress) $this->cardAddress = $this->cardAddress;
        if($this->invoiceCost) $this->invoiceCost = $this->invoiceCost;
		if($this->eotp) $this->eotp = $this->eotp;
        $this->save();
        return $this->query;
    }


	/**
	 * Edit function
	 * @param  int $eoid event options eoid
	 * @return obj      event options update status
	 */
    public function edit($eoid) {
        $this->load(array('eoid=?',$eoid));
        // $this->copyFrom('POST');
        $newData = [];
        $this->copyFrom('POST', function($data) {
            foreach ($data as $key => $value) {
                $newData[$key] = Controller::sanitizeDatas($value);
            }
            return $newData;
        });
        if($this->showContact) $this->showContact = $this->showContact == "on" ? 1 : 0;
		if($this->withSms) $this->withSms = $this->withSms == "on" ? 1 : 0;
		if($this->withRepr) $this->withRepr = $this->withRepr == "on" ? 1 : 0;
		if($this->withAcc) $this->withAcc = $this->withAcc == "on" ? 1 : 0;
		if($this->sendType) $this->sendType = $this->sendType == "on" ? 1 : 0;
		if($this->sendType) $this->sendType = $this->sendType == "on" ? 1 : 0;
		if($this->withBusinessCard) $this->withBusinessCard = $this->withBusinessCard == "on" ? 1 : 0;
        if($this->cardAddress) $this->cardAddress = $this->cardAddress;
        if($this->invoiceCost) $this->invoiceCost = $this->invoiceCost;
        if($this->eotp) $this->eotp = $this->eotp;

        $this->update();
    }


    /**
     * Delete function
     * @param  int $eoid event options eoid
     * @return bool     deletion status
     */
    public function delete($eoid) {
        $this->load(array('eoid=?',$eoid));
        $this->erase();
    }

}
