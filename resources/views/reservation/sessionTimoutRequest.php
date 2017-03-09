<?php
    use App\Data\ReservationSession;
    use App\Data\TDGs\ReservationSessionTDG;


function endSessionRequest(){
    $session = new ReservationSession(Auth::id(), null, null);
    $sessionTDG = ReservationSessionTDG::getInstance();
    $sessionTDG->endSession($session);
}



?>