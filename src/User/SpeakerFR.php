<?php

namespace User;

use Sanitize\Sanitize;

class SpeakerFR extends Speaker
{

    public function generate()
    {
        $html = null;

        foreach ($this->messages as $value) {

            switch ($value['content']) {

                //LOGIN / REGSTRATION

                case 'error-registration':
                    $value['content'] = 'Registration failed. Do you have already an account with this email?';
                    $value['background'] = 'error';
                    break;

                case 'error-form-fill':
                    $value['content'] = 'Please finish your form before send it.';
                    $value['background'] = 'info';
                    break;

                case 'error-agreement':
                    $value['content'] = 'You need to accept our conditions to use our services.';
                    $value['background'] = 'error';
                    break;

                case 'error-password-valid':
                    $value['content'] = 'Password is too short. Min 6 characters';
                    $value['background'] = 'error';
                    break;

                case 'error-password-match':
                    $value['content'] = 'Passwords don\'t match.';
                    $value['background'] = 'error';
                    break;

                case 'success-account-created':
                    $value['content'] = 'Account created successfully. Please wait our validation.';
                    $value['background'] = 'success';
                    break;

                case 'error-login-invalid' :
                    $value['content'] = 'Please verify your login and password.';
                    $value['background'] = 'error';
                    break;

                case 'error-login-require' :
                    $value['content'] = 'Please connect to access our services.';
                    $value['background'] = 'error';
                    break;

                case 'success-disconnect' :
                    $value['content'] = 'Disconnected successfully.';
                    $value['background'] = 'success';
                    break;

                // READ WRITE UPDATE OPERATION

                case 'success-add':
                    $value['content'] = 'Created successfully.';
                    $value['background'] = 'success';
                    break;

                case 'error-add':
                    $value['content'] = 'Error during creation.';
                    $value['background'] = 'error';
                    break;

                case 'success-update' :
                    $value['content'] = 'Updated successfully !';
                    $value['background'] = 'success';
                    break;

                case 'update-error' :
                    $value['content'] = 'Error during update.';
                    $value['background'] = 'error';
                    break;

                default:
                    break;
            }

            switch ($value['background']) {
                case 'success' :
                    $value['background'] = self::COLOR_SUCCESS;
                    break;
                case 'error' :
                    $value['background'] = self::COLOR_ERROR;
                    break;
                case 'info' :
                    $value['background'] = self::COLOR_INFO;
                    break;
                case 'temporary' :
                    $value['background'] = self::COLOR_TEMPORARY;
                    break;
                default:
                    break;
            }

            $html .= '<div class="speaker" style="background-color: ' . $value['background'] . ';">' . $value['content'];
            if ($value['special'] != null) $html .= '(' . $value['special'] . ')';
            $html .= '</div>';
        }

        return $html;
    }
}