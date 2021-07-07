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

/* patron/guide/pluslets/pluslet.html.twig */
class __TwigTemplate_670233310f7da480dd1bb294a94315757865e0afdae1e16a783d400805981af6 extends Template
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
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patron/guide/pluslets/pluslet.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patron/guide/pluslets/pluslet.html.twig"));

        // line 1
        echo "<div class=\"px-2\">
    <div class=\"card\">
        <div class=\"card-header bg-primary text-white\">
            ";
        // line 4
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["pluslet"]) || array_key_exists("pluslet", $context) ? $context["pluslet"] : (function () { throw new RuntimeError('Variable "pluslet" does not exist.', 4, $this->source); })()), "title", [], "any", false, false, false, 4), "html", null, true);
        echo "
         </div>
        <div class=\"card-body\">
            ";
        // line 7
        echo twig_get_attribute($this->env, $this->source, (isset($context["pluslet"]) || array_key_exists("pluslet", $context) ? $context["pluslet"] : (function () { throw new RuntimeError('Variable "pluslet" does not exist.', 7, $this->source); })()), "body", [], "any", false, false, false, 7);
        echo "
        </div>
    </div>
</div>
";
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "patron/guide/pluslets/pluslet.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  54 => 7,  48 => 4,  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<div class=\"px-2\">
    <div class=\"card\">
        <div class=\"card-header bg-primary text-white\">
            {{ pluslet.title }}
         </div>
        <div class=\"card-body\">
            {{ pluslet.body | raw }}
        </div>
    </div>
</div>
", "patron/guide/pluslets/pluslet.html.twig", "/Users/cbrownroberts/SubjectsPlus5/SP5-Docker-Symfony/sp5-docker/SubjectsPlus/templates/patron/guide/pluslets/pluslet.html.twig");
    }
}
