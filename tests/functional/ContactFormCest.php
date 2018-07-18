<?php
use FunctionalTester;

class ContactFormCest 
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage(['site/contact']);
    }

    public function openContactPage(\FunctionalTester $I)
    {
        $I->see('Contact', 'h1');        
    }

    public function submitEmptyForm(\FunctionalTester $I)
    {
        $I->see('Contact');
        $I->submitForm('#contact-form', []);
        $I->expectTo('see validations errors');
        $I->see('Contact', 'h1');
        $I->see('Naam mag niet leeg zijn.', '.help-block-error');
        $I->see('Onderwerp mag niet leeg zijn.');
        $I->see('Tekst mag niet leeg zijn.');
        $I->see('De verificatiecode is onjuist.');
    }

    public function submitFormWithIncorrectEmail(\FunctionalTester $I)
    {
        $I->see('Contact');
        $I->submitForm('#contact-form', [
            'ContactForm[name]' => 'tester',
            'ContactForm[email]' => 'tester.email',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
            'ContactForm[verifyCode]' => 'wrong',
        ]);
        $I->expectTo('see that email address is wrong');
        $I->dontSee('Name cannot be blank', '.help-inline');
        $I->see('Email is geen geldig emailadres.');
        $I->dontSee('Onderwerp mag niet leeg zijn.');
        $I->dontSee('Tekst mag niet leeg zijn.');
        $I->see('De verificatiecode is onjuist.');        
    }

    public function submitFormSuccessfully(\FunctionalTester $I)
    {
        $I->see('Contact');
        $I->submitForm('#contact-form', [
            'ContactForm[name]' => 'tester',
            'ContactForm[email]' => 'tester@example.com',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
            'ContactForm[verifyCode]' => 'testme',
        ]);
        $I->seeEmailIsSent();
        $I->dontSeeElement('#contact-form');
        $I->see('Thank you for contacting us. We will respond to you as soon as possible.');        
    }
}
