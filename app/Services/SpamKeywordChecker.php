<?php

namespace App\Services;

class SpamKeywordChecker
{
    /** @var string[] */
    private array $keywords = [
        'act now', 'amazing deal', 'apply now', 'as seen on', 'be your own boss', 'best price', 'big bucks',
        'billion dollars', 'bonus', 'buy direct', 'cash bonus', 'cashcashcash', 'casino', 'cheap', 'click below',
        'click here', 'congratulations', 'credit card', 'cures', 'deal ends', 'dear friend', 'discount', 'double your',
        'earn cash', 'earn money', 'exclusive deal', 'extra income', 'fast cash', 'financial freedom', 'free access',
        'free gift', 'free grant', 'free hosting', 'free info', 'free investment', 'free money', 'free offer',
        'free trial', 'full refund', 'get paid', 'giveaway', 'guarantee', 'hidden charges', 'increase sales',
        'instant access', 'limited time', 'lose weight', 'lowest price', 'make money', 'millionaire', 'miracle',
        'money back', 'multi level marketing', 'no catch', 'no cost', 'no credit check', 'no fees', 'no obligation',
        'no purchase necessary', 'not spam', 'one hundred percent free', 'online biz opportunity', 'order now',
        'passwords', 'prize', 'promise you', 'refinance', 'removes wrinkles', 'risk free', 'satisfaction guaranteed',
        'save big', 'special promotion', 'this is not spam', 'urgent', 'viagra', 'winner', 'work from home',
        'xxx', 'you have been selected', 'you won',
    ];

    public function containsSuspiciousKeyword(string $subject, string $htmlBody): bool
    {
        $text = mb_strtolower($subject.' '.strip_tags($htmlBody));

        foreach ($this->keywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }

        return false;
    }

    public function keywords(): array
    {
        return $this->keywords;
    }
}
