<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Service\TokenService;

class TokenExtension extends AbstractExtension
{
    /**
     * TokenService
     *
     * @var TokenService $tokenService
     */
    private $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }
    
    public function getFilters()
    {
        return [
            new TwigFilter('update_tokens_from_html', [$this, 'updateTokensFromHTML'])
        ];
    }

    public function updateTokensFromHTML(string $html) {
        return $this->tokenService->updateTokens($html);
    }
}