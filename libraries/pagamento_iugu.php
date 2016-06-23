<?php
class Pagamento_iugu {
    
    
    
    public function pagamentoAvulso($dadosPagamento, $session_data) {
        $this->CI =& get_instance();
        
        $this->CI->load->model("pagamento_iugu_model");
        if (!isset($_SESSION)) {
            session_start();
        }

        //pode ser cartão ou boleto
        $tipo_pagamento = $dadosPagamento['tipo_pag'];

        //pagamento com boleto é sempre a vista, não pode ser parcelado
        $parcelas = ($tipo_pagamento == 'cartao') ? $dadosPagamento['numParcelas'] : 1;

        $valor = str_replace("R$", "", str_replace(",", ".", str_replace(".", "", $dadosPagamento['valor'])));
        $valor = number_format($valor, 2, "", "");

        if (isset($dadosPagamento['email_pagamento'])) {
            $email = $dadosPagamento['email_pagamento'];
        } else if (isset($session_data->salaoEmail)) {
            $email = $session_data->salaoEmail;
        } else {
            $email = $dadosPagamento['email_pagamento'];
        }
        //$email = "andre.vicente@salaovip9.com.br";

        if (isset($dadosPagamento['nome_pagamento'])) {
            $nome = $dadosPagamento['nome_pagamento'];
        } else if (isset($session_data->salaoNome)) {
            $nome = $session_data->salaoNome;
        } else {
            $nome = $dadosPagamento['nome_pagamento'];
        }

        if (isset($dadosPagamento['nome_pagamento'])) {
            $salaoId = decode_ci($dadosPagamento['salao_id']);
        } else if (isset($session_data->salaoId)) {
            $salaoId = $session_data->salaoId;
        } else {
            $salaoId = decode_ci($dadosPagamento['salao_id']);
        }

        //apenas para pagamento com cartão.
        //cria o tipo de pagamento para depois vincular ao cliente
        if ($tipo_pagamento == 'cartao') {
            $dados = $dadosPagamento["dados"];
            $credit_card_number = str_replace(".", "", $dados->numeroCartao);
            //cartão teste
            //$credit_card_number = '4111111111111111';
            //$credit_card_number = '4073020000000002';

            $credit_card_cvv = $dados->cvv;
            $mes_valdade = $dados->mesValidade;
            $ano_valdade = $dados->anoValidade;
            $credit_card_name = $dados->nome;
            $nome = explode(" ", $credit_card_name);
            $firt_name = $nome[0];
            $last_name = str_replace($firt_name . " ", "", $credit_card_name);
            $nome = $credit_card_name;
        }

        //verifica se o cliente já está cadastrado no IUGU
        $cliente = $this->CI->pagamento_iugu_model->buscaCliente($email);
        if (!$cliente) {
            //se não existir cadastra o cliente
            $cliente = $this->CI->pagamento_iugu_model->criarCliente(array("email" => $email, "nome" => $nome, "cpf_cnpj" => ''));
        }
        //se houver erro ao pegar código do cliente retorna erro
        if (!$cliente) {
            echo json_encode(array("sucesso" => false, "dados" => "Erro ao cadastrar cliente."));
            exit();
        }

        //apenas para pagamento com cartão. Valida o cartão e gera o token de pagamento
        if ($tipo_pagamento == 'cartao') {
            $dadosCartao = array(
                "number" => $credit_card_number,
                "verification_value" => $credit_card_cvv,
                "first_name" => $firt_name,
                "last_name" => $last_name,
                "month" => $mes_valdade,
                "year" => $ano_valdade
            );

            //gera token de pagamento com os dados do cartão
            $payment_token = $this->CI->pagamento_iugu_model->geraTokenPagamento($dadosCartao);

            if (isset($payment_token['errors'])) {
                if (isset($payment_token['errors']['number'][0])) {
                    $erroMsg = "Número do cartão inválido.";
                } else if (isset($payment_token['errors']['year'][0])) {
                    $erroMsg = "Cartão expirado.";
                } else {
                    $erroMsg = "Erro ao cadastrar forma de pagamento.";
                }
                echo json_encode(array("sucesso" => false, "dados" => $erroMsg));
                exit();
            }

            if ($parcelas == 1) {

                $dadosCartaoCliente = array(
                    'customer_id' => $cliente,
                    'token' => $payment_token,
                    "number" => $credit_card_number,
                    "verification_value" => $credit_card_cvv,
                    "first_name" => $firt_name,
                    "last_name" => $last_name,
                    "month" => $mes_valdade,
                    "year" => $ano_valdade
                );

                //vincula o cliente a forma de pagamento
                $pagamento = $this->CI->pagamento_iugu_model->vinculaClienteCartao($dadosCartaoCliente);

                if (!$pagamento) {

                    if (isset($payment_token['errors']['data'][0])) {
                        $erroMsg = "Cartão inválido.";
                    } else if (isset($payment_token['errors']['year'][0])) {
                        $erroMsg = "Cartão expirado.";
                    } else {
                        $erroMsg = "Erro ao cadastrar forma de pagamento.";
                    }

                    echo json_encode(array("sucesso" => false, "dados" => $erroMsg));
                    exit();
                }
            }
        }

        $retorno = false;

        $nome_produto = $dadosPagamento['titulo'];
        if ($dadosPagamento['nome_produto'] != '') {
            $nome_produto = $dadosPagamento['nome_produto'];
        }

        $quantidade = 1;
        if (isset($dadosPagamento['quantidade']) && $dadosPagamento['quantidade'] != '') {
            $quantidade = $dadosPagamento['quantidade'];
        }

        //apenas para boleto
        if ($tipo_pagamento == 'cartao') {
            $dadosCobranca = array(
                "token" => $payment_token,
                "months" => $parcelas,
                "email" => $email,
                "cliente_id" => $cliente,
                "description" => $nome_produto,
                "price_cents" => $valor,
                "cpf_cnpj" => '',
                "name" => $credit_card_name
            );
            //gera pagamento
            $retorno = $this->CI->pagamento_iugu_model->geraPagamentoCartao($dadosCobranca, $quantidade);
        } else {
            //apenas para boleto
            $dadosBoleto = array(
                "email" => $email,
                "cliente_id" => $cliente,
                "description" => $nome_produto,
                "price_cents" => $valor,
                "name" => $nome
            );
            //gera 1 boleto simples
            $retorno = $this->CI->pagamento_iugu_model->gerarBoleto($dadosBoleto, $quantidade);
        }


        if (isset($retorno['success']) && $retorno['success'] == 1 && $retorno['url'] != '') {
            // SUCESSO!!!
            return array(true, "Pagamento gerado com sucesso!", $retorno['url']);
        } else if (isset($retorno['message'])) {
            return array(false, "Erro ao gerar pagamento. " . $retorno['message'], NULL);
        } else {
            if (isset($retorno['errors']) && $retorno['errors'] != '') {
                return array(false, $retorno['errors'], NULL);
            } else {
                return array(false, "Erro ao enviar pagamento", NULL);
            }
        }        
    }
    

}