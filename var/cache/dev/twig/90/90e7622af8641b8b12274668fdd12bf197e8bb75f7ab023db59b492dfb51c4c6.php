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

/* patron/databases/database_row.html.twig */
class __TwigTemplate_cc46d4132bf11bea5423fb9bb64eb719552b6d9bf5699fbbf8c8dd8fdcec5990 extends Template
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
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patron/databases/database_row.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patron/databases/database_row.html.twig"));

        // line 1
        echo "<tr>
    <td>
        ";
        // line 3
        $context["location"] = twig_first($this->env, twig_get_attribute($this->env, $this->source, (isset($context["result"]) || array_key_exists("result", $context) ? $context["result"] : (function () { throw new RuntimeError('Variable "result" does not exist.', 3, $this->source); })()), "location", [], "any", false, false, false, 3));
        // line 4
        echo "        <button class=\"btn btn-info\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#collapse";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["location"]) || array_key_exists("location", $context) ? $context["location"] : (function () { throw new RuntimeError('Variable "location" does not exist.', 4, $this->source); })()), "locationId", [], "any", false, false, false, 4), "html", null, true);
        echo "\" aria-expanded=\"false\" aria-controls=\"collapse";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["location"]) || array_key_exists("location", $context) ? $context["location"] : (function () { throw new RuntimeError('Variable "location" does not exist.', 4, $this->source); })()), "locationId", [], "any", false, false, false, 4), "html", null, true);
        echo "\">
            <span class=\"bi bi-info-circle\"></span>
        </button>
        <a href=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->extensions['App\Twig\Database']->databaseUrl(twig_get_attribute($this->env, $this->source, (isset($context["location"]) || array_key_exists("location", $context) ? $context["location"] : (function () { throw new RuntimeError('Variable "location" does not exist.', 7, $this->source); })()), "location", [], "any", false, false, false, 7), twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["location"]) || array_key_exists("location", $context) ? $context["location"] : (function () { throw new RuntimeError('Variable "location" does not exist.', 7, $this->source); })()), "accessRestrictions", [], "any", false, false, false, 7), "restrictionsId", [], "any", false, false, false, 7)), "html", null, true);
        echo "\">
        ";
        // line 8
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["result"]) || array_key_exists("result", $context) ? $context["result"] : (function () { throw new RuntimeError('Variable "result" does not exist.', 8, $this->source); })()), "title", [], "any", false, false, false, 8), "html", null, true);
        echo "
        </a>
        <div class=\"collapse\" id=\"collapse";
        // line 10
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["location"]) || array_key_exists("location", $context) ? $context["location"] : (function () { throw new RuntimeError('Variable "location" does not exist.', 10, $this->source); })()), "locationId", [], "any", false, false, false, 10), "html", null, true);
        echo "\">
            <div class=\"card card-body\">";
        // line 11
        echo twig_get_attribute($this->env, $this->source, (isset($context["result"]) || array_key_exists("result", $context) ? $context["result"] : (function () { throw new RuntimeError('Variable "result" does not exist.', 11, $this->source); })()), "description", [], "any", false, false, false, 11);
        echo "</div>
        </div>
    </td>
</tr>
";
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "patron/databases/database_row.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 11,  67 => 10,  62 => 8,  58 => 7,  49 => 4,  47 => 3,  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<tr>
    <td>
        {% set location = result.location | first %}
        <button class=\"btn btn-info\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#collapse{{ location.locationId }}\" aria-expanded=\"false\" aria-controls=\"collapse{{ location.locationId }}\">
            <span class=\"bi bi-info-circle\"></span>
        </button>
        <a href=\"{{database_url(location.location, location.accessRestrictions.restrictionsId)}}\">
        {{result.title}}
        </a>
        <div class=\"collapse\" id=\"collapse{{location.locationId}}\">
            <div class=\"card card-body\">{{ result.description | raw }}</div>
        </div>
    </td>
</tr>
", "patron/databases/database_row.html.twig", "/Users/cbrownroberts/SubjectsPlus5/SP5-Docker-Symfony/sp5-docker/SubjectsPlus/templates/patron/databases/database_row.html.twig");
    }
}
