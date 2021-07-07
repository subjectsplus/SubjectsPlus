<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* patron/shared/header.html.twig */
class __TwigTemplate_9100a94dbb1cf8321bc0326e638ec7f77ab4a380b522eb4334f55ff4f9ebfdae extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patron/shared/header.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patron/shared/header.html.twig"));

        // line 1
        echo "<nav class=\"navbar-expand-lg site-navbar-slim-nosliver navbar-dark\">
    <div class=\"container position-relative\">
        <a class=\"navbar-brand\" href=\"/\">
        <!-- TODO add a good alt text for the logo -->
            <img src=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("build/assets/images/public/logo-placeholder.png"), "html", null, true);
        echo "\" alt=\"logo\" class=\"d-inline-block d-lg-block\">
        </a>
        <button class=\"navbar-toggler js-sidebar-toggler d-block d-lg-none\" data-button-options='{\"modifiers\":\"left\",\"wrapText\":false}' aria-label=\"Toggle sidebar\">
            <div class=\"nav-icon\">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>
    </div>

    <div class=\"container\">
        <div class=\"collapse navbar-collapse\" id=\"siteNavigation\" data-set=\"bs\">
            <ul class=\"navbar-nav site-nav js-append-around\">
                <li class=\"nav-item active\">
                    <a class=\"nav-link\" href=\"/\">";
        // line 23
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Home"), "html", null, true);
        echo "</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"/\">";
        // line 26
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Guides"), "html", null, true);
        echo "</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"/subjects/databases.php\">";
        // line 29
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Databases"), "html", null, true);
        echo "</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"/subjects/staff.php\">";
        // line 32
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Staff list"), "html", null, true);
        echo "</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"/subjects/faq.php\">";
        // line 35
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("FAQs"), "html", null, true);
        echo "</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"/subjects/talkback.php\">";
        // line 38
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans("Talkback"), "html", null, true);
        echo "</a>
                </li>
            </ul>
        </div>
    </div>
</nav>";
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "patron/shared/header.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  100 => 38,  94 => 35,  88 => 32,  82 => 29,  76 => 26,  70 => 23,  49 => 5,  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<nav class=\"navbar-expand-lg site-navbar-slim-nosliver navbar-dark\">
    <div class=\"container position-relative\">
        <a class=\"navbar-brand\" href=\"/\">
        <!-- TODO add a good alt text for the logo -->
            <img src=\"{{ asset('build/assets/images/public/logo-placeholder.png') }}\" alt=\"logo\" class=\"d-inline-block d-lg-block\">
        </a>
        <button class=\"navbar-toggler js-sidebar-toggler d-block d-lg-none\" data-button-options='{\"modifiers\":\"left\",\"wrapText\":false}' aria-label=\"Toggle sidebar\">
            <div class=\"nav-icon\">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>
    </div>

    <div class=\"container\">
        <div class=\"collapse navbar-collapse\" id=\"siteNavigation\" data-set=\"bs\">
            <ul class=\"navbar-nav site-nav js-append-around\">
                <li class=\"nav-item active\">
                    <a class=\"nav-link\" href=\"/\">{{ 'Home' | trans }}</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"/\">{{ 'Guides' | trans }}</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"/subjects/databases.php\">{{ 'Databases' | trans }}</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"/subjects/staff.php\">{{ 'Staff list' | trans }}</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"/subjects/faq.php\">{{ 'FAQs' | trans }}</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"/subjects/talkback.php\">{{ 'Talkback' | trans }}</a>
                </li>
            </ul>
        </div>
    </div>
</nav>", "patron/shared/header.html.twig", "/home/site/wwwroot/templates/patron/shared/header.html.twig");
    }
}
