<?php

namespace TeaCampus\CommonBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

class TeaUtils
{
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function since($date, $locale)
    {

        $time = time() - $date->getTimestamp();

        $tokens = array (
            'fr' => array(
                31536000 => 'année',
                2592000 => 'mois',
                604800 => 'semaine',
                86400 => 'jour',
                3600 => 'heure',
                60 => 'minute',
                1 => 'seconde'
            ),
            'en' => array(
                31536000 => 'year',
                2592000 => 'month',
                604800 => 'week',
                86400 => 'day',
                3600 => 'hour',
                60 => 'minute',
                1 => 'second'
            )
        );

        $token_local = (isset($tokens[$locale]))? $tokens[$locale] : array();

        foreach ($token_local as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            $humanTiming =  $numberOfUnits.' '.$text.(($numberOfUnits>1 && $text != 'mois')?'s':'');
            break;
        }

        if($locale == "fr"){
            return 'Il y a '.$humanTiming;
        }elseif($locale == "en"){
            return $humanTiming.' ago';
        }else{
            return "";
        }

    }

    public function sinceOrDateTime($date, $locale, $withTime = false)
    {

        $time = time() - $date->getTimestamp();

        $tokens = array (
            'fr' => array(
                'time' => array(
                    31536000 => 'année',
                    2592000 => 'mois',
                    604800 => 'semaine',
                    86400 => 'jour',
                    3600 => 'heure',
                    60 => 'minute',
                    1 => 'seconde'
                ),
                'days' => array(
                    'yesterday' => 'Hier',
                    'today' => 'Aujourd\'hui'
                ),
                'long_format' => '%d MONTH_HERE %Y',
                'long_format_time' => '%d MONTH_HERE %Y, %H:%M',
                'month' => array ("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre")
            ),
            'en' => array(
                'time' => array(
                    31536000 => 'year',
                    2592000 => 'month',
                    604800 => 'week',
                    86400 => 'day',
                    3600 => 'hour',
                    60 => 'minute',
                    1 => 'second'
                ),
                'days' => array(
                    'yesterday' => 'Yesterday',
                    'today' => 'Today'
                ),
                'long_format' => '%Y MONTH_HERE %d',
                'long_format_time' => '%Y MONTH_HERE %d, %H:%M',
                'month' => array ("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December")
            )
        );

        if($time<18000){
            $token_local = (isset($tokens[$locale]['time']))? $tokens[$locale]['time'] : array();
            foreach ($token_local as $unit => $text) {
                if ($time < $unit) continue;
                $numberOfUnits = floor($time / $unit);
                $humanTiming =  $numberOfUnits.' '.$text.(($numberOfUnits>1 && $text != 'mois')?'s':'');
                break;
            }
            if($locale == "fr"){
                return 'Il y a '.$humanTiming;
            }elseif($locale == "en"){
                return $humanTiming.' ago';
            }else{
                return "";
            }
        }elseif($time<84600){
            $token_today = (isset($tokens[$locale]['days']['today']))? $tokens[$locale]['days']['today'] : array();
            return $date->format('H:i').', '.$token_today;
        }elseif($time<172800){
            $token_yesterday = (isset($tokens[$locale]['days']['yesterday']))? $tokens[$locale]['days']['yesterday'] : array();
            return $date->format('H:i').', '.$token_yesterday;
        }else{
            if($withTime)
                $token_format = (isset($tokens[$locale]['long_format_time']))? $tokens[$locale]['long_format_time'] : array();
            else
                $token_format = (isset($tokens[$locale]['long_format']))? $tokens[$locale]['long_format'] : array();

            // On recupere le mois
            $token_month = (isset($tokens[$locale]['month']))? $tokens[$locale]['month'] : array();
            $month_nb = intval(date_format($date, "m"))-1;
            $month = (isset($token_month[$month_nb]))? $token_month[$month_nb] : "";

            return str_replace("MONTH_HERE", $month, strftime($token_format, $date->getTimestamp()));
        }

    }


}