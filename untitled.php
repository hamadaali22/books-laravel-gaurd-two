
    public function forgot_password(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'email' => "required|email",
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject($this->getEmailSubject());
                });
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return \Response::json(array("status" => 200, "message" => trans($response), "data" => array()));
                    case Password::INVALID_USER:
                        return \Response::json(array("status" => 400, "message" => trans($response), "data" => array()));
                }
            } catch (\Swift_TransportException $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            } catch (Exception $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            }
        }
        return \Response::json($arr);
    }



    // public function change_password(Request $request)
    // {

    //     // $gg= Auth::guard('doctor-api')->user();
    //     // dd($gg);
    //     $input = $request->all();
    //     $userid = Doctor::where("id" , $request->id)->first();
    //     // $userid = Auth::guard('doctor-api')->user()->id;
    //     // dd($userid);
    //     $rules = array(
    //         'old_password' => 'required',
    //         'new_password' => 'required',
    //         'confirm_password' => 'required|same:new_password',
    //     );
    //     $validator = Validator::make($input, $rules);
    //     if ($validator->fails()) {
    //         $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
    //     } else {
    //         try {
    //             dd('12345');
    //             $cc=$userid->password;
    //             dd($cc);
    //             $ss=bcrypt($request->old_password);
    //              dd($ss);
    //             if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
    //                 $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
    //             } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
    //                 $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
    //             } else {
    //                 Doctor::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
    //                 $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
    //             }
    //         } catch (\Exception $ex) {
    //             if (isset($ex->errorInfo[2])) {
    //                 $msg = $ex->errorInfo[2];
    //             } else {
    //                 $msg = $ex->getMessage();
    //             }
    //             $arr = array("status" => 400, "message" => $msg, "data" => array());
    //         }
    //     }
    //     return \Response::json($arr);
    // }


    MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"