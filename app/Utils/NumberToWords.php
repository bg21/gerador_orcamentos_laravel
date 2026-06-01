<?php

namespace App\Utils;

class NumberToWords
{
    private static $unidades = [
        0 => 'zero', 1 => 'um', 2 => 'dois', 3 => 'três', 4 => 'quatro',
        5 => 'cinco', 6 => 'seis', 7 => 'sete', 8 => 'oito', 9 => 'nove',
        10 => 'dez', 11 => 'onze', 12 => 'doze', 13 => 'treze', 14 => 'quatorze',
        15 => 'quinze', 16 => 'dezesseis', 17 => 'dezessete', 18 => 'dezoito', 19 => 'dezenove'
    ];

    private static $dezenas = [
        2 => 'vinte', 3 => 'trinta', 4 => 'quarenta', 5 => 'cinquenta',
        6 => 'sessenta', 7 => 'setenta', 8 => 'oitenta', 9 => 'noventa'
    ];

    private static $centenas = [
        1 => 'cento', 2 => 'duzentos', 3 => 'trezentos', 4 => 'quatrocentos',
        5 => 'quinhentos', 6 => 'seiscentos', 7 => 'setecentos', 8 => 'oitocentos', 9 => 'novecentos'
    ];

    public static function converterMonetario($valor)
    {
        if ($valor == 0) {
            return 'ZERO REAIS';
        }

        // Separate reais and centavos
        $reais = floor($valor);
        $centavos = round(($valor - $reais) * 100);

        $extensoReais = '';
        if ($reais > 0) {
            $extensoReais = self::converterInteiro($reais);
            $extensoReais .= $reais == 1 ? ' real' : ' reais';
        }

        $extensoCentavos = '';
        if ($centavos > 0) {
            $extensoCentavos = self::converterInteiro($centavos);
            $extensoCentavos .= $centavos == 1 ? ' centavo' : ' centavos';
        }

        if ($extensoReais && $extensoCentavos) {
            $resultado = $extensoReais . ' e ' . $extensoCentavos;
        } else {
            $resultado = $extensoReais ?: $extensoCentavos;
        }

        return mb_strtoupper(trim($resultado));
    }

    private static function converterInteiro($numero)
    {
        if ($numero == 100) return 'cem';
        
        $grupos = [];
        $milhares = 0;
        
        while ($numero > 0) {
            $grupo = $numero % 1000;
            if ($grupo > 0) {
                $extensoGrupo = self::converterGrupoTresDigitos($grupo);
                
                if ($milhares == 1) {
                    $extensoGrupo .= ' mil';
                } elseif ($milhares == 2) {
                    $extensoGrupo .= $grupo == 1 ? ' milhão' : ' milhões';
                } elseif ($milhares == 3) {
                    $extensoGrupo .= $grupo == 1 ? ' bilhão' : ' bilhões';
                }
                
                array_unshift($grupos, $extensoGrupo);
            }
            $numero = floor($numero / 1000);
            $milhares++;
        }
        
        return implode(' e ', $grupos);
    }

    private static function converterGrupoTresDigitos($numero)
    {
        if ($numero == 0) return '';
        if ($numero == 100) return 'cem';
        
        $partes = [];
        
        $centena = floor($numero / 100);
        $resto = $numero % 100;
        
        if ($centena > 0) {
            $partes[] = self::$centenas[$centena];
        }
        
        if ($resto > 0) {
            if ($resto < 20) {
                $partes[] = self::$unidades[$resto];
            } else {
                $dezena = floor($resto / 10);
                $unidade = $resto % 10;
                
                $partes[] = self::$dezenas[$dezena];
                
                if ($unidade > 0) {
                    $partes[] = self::$unidades[$unidade];
                }
            }
        }
        
        return implode(' e ', $partes);
    }
}
