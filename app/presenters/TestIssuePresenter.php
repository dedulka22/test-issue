<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI;
use Nette\Application\UI\Form as Form;
use App\Model\TestIssueManager;

class TestIssuePresenter extends BasePresenter {


    /** @var TestIssueManager Instancia triedy modelu kalkulačky */
    private $testIssueManager;

    /**
     * TestIssuePresenter constructor.
     * @param TestIssueManager $testIssueManager
     */
    public function __construct(TestIssueManager $testIssueManager)
    {
        parent::__construct();
        $this->testIssueManager = $testIssueManager;
    }

    public function renderResult($result)
    {
        // predanie výsledku do šablóny
        $this->template->result = $this->testIssueManager->calculate($result);
    }


    public function renderParser($json) {
        $this->template->parser = $this->testIssueManager->jsonDecode($json);
    }

    protected function createComponentCalculateForm() {
        $form = new UI\Form;
        $form->addTextArea('value', 'Value:');
        $func = [
            'calculate' => 'Calculate',
            'JSONparser' => 'JSON parser',
        ];
        $form->addRadioList('function', 'Function:', $func);
        
        $form->addSubmit('send', 'Send');

        $form->onSuccess[] = array($this, 'calculateFormSucceeded');
        return $form;
    }

    public function calculateFormSucceeded(UI\Form $form, $values) {

        $calc = $values['function'] == "calculate";
        $pars = $values['function'] == "JSONparser";

        if ($calc) {
            $result = $this->template->result = $this->testIssueManager->calculate($values->value);
            $this->redirect("TestIssue:result", ['function' => $values['function'], 'result' => $result]);
        } elseif ($pars) {
            $parser = $this->template->parser = $this->testIssueManager->jsonDecode($values->value);
            bdump($parser);
            $this->redirect("TestIssue:parser", ['function' => $values['function']]);
        }
    }


}

