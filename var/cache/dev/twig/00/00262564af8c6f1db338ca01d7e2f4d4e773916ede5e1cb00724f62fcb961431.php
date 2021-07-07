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

/* patron/guide/tabs/tabs.html.twig */
class __TwigTemplate_aef96618d124bf28ee32d1434f78fe93453b334fdcdf2e98607fe36016ab0e03 extends Template
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
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patron/guide/tabs/tabs.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patron/guide/tabs/tabs.html.twig"));

        // line 1
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["tabs"]) || array_key_exists("tabs", $context) ? $context["tabs"] : (function () { throw new RuntimeError('Variable "tabs" does not exist.', 1, $this->source); })()));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["tab"]) {
            // line 2
            echo "    ";
            $context["class"] = ((twig_get_attribute($this->env, $this->source, $context["loop"], "first", [], "any", false, false, false, 2)) ? ("nav-link active") : ("nav-link"));
            // line 3
            echo "    <li class=\"nav-item\">
    ";
            // line 4
            if (twig_get_attribute($this->env, $this->source, $context["tab"], "external_url", [], "any", false, false, false, 4)) {
                // line 5
                echo "    <a class=\"";
                echo twig_escape_filter($this->env, (isset($context["class"]) || array_key_exists("class", $context) ? $context["class"] : (function () { throw new RuntimeError('Variable "class" does not exist.', 5, $this->source); })()), "html", null, true);
                echo "\" href=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tab"], "external_url", [], "any", false, false, false, 5), "html", null, true);
                echo "\">Tab ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tab"], "label", [], "any", false, false, false, 5), "html", null, true);
                echo "</a>
    ";
            } else {
                // line 7
                echo "        <button class=\"";
                echo twig_escape_filter($this->env, (isset($context["class"]) || array_key_exists("class", $context) ? $context["class"] : (function () { throw new RuntimeError('Variable "class" does not exist.', 7, $this->source); })()), "html", null, true);
                echo "\" data-bs-toggle=\"tab\" data-bs-target=\"#";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tab"], "id", [], "any", false, false, false, 7), "html", null, true);
                echo "\" type=\"button\" role=\"tab\" aria-controls=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tab"], "id", [], "any", false, false, false, 7), "html", null, true);
                echo "\">
        ";
                // line 8
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["tab"], "label", [], "any", false, false, false, 8), "html", null, true);
                echo "
        </button>
    ";
            }
            // line 11
            echo "    </li>

";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tab'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 14
        echo "

<!-- TODO: tabs that are external links -->
<!-- TODO: aria-selected -->
<!-- TODO: give these tabs real numbers -->";
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "patron/guide/tabs/tabs.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  109 => 14,  93 => 11,  87 => 8,  78 => 7,  68 => 5,  66 => 4,  63 => 3,  60 => 2,  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% for tab in tabs %}
    {% set class = loop.first ? 'nav-link active' : 'nav-link' %}
    <li class=\"nav-item\">
    {% if tab.external_url %}
    <a class=\"{{ class }}\" href=\"{{tab.external_url}}\">Tab {{ tab.label }}</a>
    {% else %}
        <button class=\"{{ class }}\" data-bs-toggle=\"tab\" data-bs-target=\"#{{tab.id}}\" type=\"button\" role=\"tab\" aria-controls=\"{{tab.id}}\">
        {{tab.label}}
        </button>
    {% endif %}
    </li>

{% endfor %}


<!-- TODO: tabs that are external links -->
<!-- TODO: aria-selected -->
<!-- TODO: give these tabs real numbers -->", "patron/guide/tabs/tabs.html.twig", "/Users/cbrownroberts/SubjectsPlus5/SP5-Docker-Symfony/sp5-docker/SubjectsPlus/templates/patron/guide/tabs/tabs.html.twig");
    }
}
