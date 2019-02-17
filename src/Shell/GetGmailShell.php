<?php

namespace App\Shell;

use Cake\Console\Shell;

/**
 * GetGmail shell command.
 */
class GetGmailShell extends Shell
{

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {
        $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
        $username = 'pennyintelligence@stockgitter.com'; # e.g somebody@gmail.com
        $password = 'stockgitter222';
        $from = 'jsereporter@jamstockex.com';
//        /* try to connect */
        $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
        $emails = imap_search($inbox, 'ALL');

        /* useful only if the above search is set to 'ALL' */
        $max_emails = 100;

        /* if any emails found, iterate through each email */
        if ($emails) {

            $count = 1;

            /* put the newest emails on top */
            rsort($emails);
            $array = [];
            /* for every email... */
            foreach ($emails as $email_number) {
                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox, $email_number, 0);
                $message = imap_fetchbody($inbox, $email_number, 2);

                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox, $email_number, 0);
                $pos = strpos($overview[0]->from, $from);
                if ($pos !== false) {
                    /* get mail message, not actually used here. 
                      Refer to http://php.net/manual/en/function.imap-fetchbody.php
                      for details on the third parameter.
                     */
                    $message = imap_fetchbody($inbox, $email_number, 2);
                    /* get mail structure */
                    $structure = imap_fetchstructure($inbox, $email_number);

                    $attachments = array();

                    /* if any attachments found... */
                    if (isset($structure->parts) && count($structure->parts)) {
                        for ($i = 0; $i < count($structure->parts); $i++) {
                            $attachments[$i] = array(
                                'is_attachment' => false,
                                'filename' => '',
                                'name' => '',
                                'attachment' => ''
                            );

                            if ($structure->parts[$i]->ifdparameters) {
                                foreach ($structure->parts[$i]->dparameters as $object) {
                                    if (strtolower($object->attribute) == 'filename') {
                                        $attachments[$i]['is_attachment'] = true;
                                        $attachments[$i]['filename'] = $object->value;
                                    }
                                }
                            }

                            if ($structure->parts[$i]->ifparameters) {
                                foreach ($structure->parts[$i]->parameters as $object) {
                                    if (strtolower($object->attribute) == 'name') {
                                        $attachments[$i]['is_attachment'] = true;
                                        $attachments[$i]['name'] = $object->value;
                                    }
                                }
                            }

                            if ($attachments[$i]['is_attachment']) {
                                $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);

                                /* 3 = BASE64 encoding */
                                if ($structure->parts[$i]->encoding == 3) {
                                    $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                                }
                                /* 4 = QUOTED-PRINTABLE encoding */ elseif ($structure->parts[$i]->encoding == 4) {
                                    $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                                }
                            }
                        }
                    }
                    /* iterate through each attachment and save it */
                    foreach ($attachments as $attachment) {
                        if ($attachment['is_attachment'] == 1) {
                            $filename = $attachment['name'];
                            if (empty($filename))
                                $filename = $attachment['filename'];

                            $array[] = $filename;

                            /* prefix the email number to the filename in case two emails
                             * have the attachment with the same file name.
                             */
                            $fp = fopen("/home/jmddata/ftp/" . $filename, "w+");
                            fwrite($fp, $attachment['attachment']);
                            fclose($fp);
                        }
                    }
                    break;
                }
                if ($count++ >= $max_emails)
                    break;
            }
        }

        /* close the connection */
        imap_close($inbox);

        echo "Done";
    }

}
