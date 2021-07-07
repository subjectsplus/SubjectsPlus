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

/* patron/index.html.twig */
class __TwigTemplate_2c6a251e81f47cb79f804e0bbd439c079e61da107d4e0012818958be783c0e70 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'javascripts' => [$this, 'block_javascripts'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "patron/base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patron/index.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patron/index.html.twig"));

        $this->parent = $this->loadTemplate("patron/base.html.twig", "patron/index.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 3
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        echo "Guide";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

    }

    // line 6
    public function block_javascripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 7
        echo "     ";
        $this->displayParentBlock("javascripts", $context, $blocks);
        echo "
     ";
        // line 8
        echo $this->extensions['Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension']->renderWebpackScriptTags("patron_index");
        echo "
";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

    }

    // line 11
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 12
        echo "
";
        // line 13
        $this->loadTemplate("patron/shared/searchbar.html.twig", "patron/index.html.twig", 13)->display(twig_array_merge($context, ["action" => "/subjects/search.php", "label" => "Find guides"]));
        // line 14
        echo "

<section class=\"section section-half-top\">
    <div class=\"container\">
        <div class=\"row\">
            <div class=\"col-lg-8\">
                <div class=\"pills-container mt-5\">
                    <ul class=\"nav nav-pills justify-content-around justify-content-md-start\" id=\"pills-tab-guides\" role=\"tablist\">
                    
                    <li class=\"nav-item\">
                        <a class=\"nav-link no-decoration default active\" id=\"show-Collection\" name=\"showCollection\" data-toggle=\"pill\"
                        href=\"#section-Collection\" role=\"tab\" aria-controls=\"section-Collection\"
                        aria-selected=\"true\">";
        // line 26
        echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Guide Collections", [], "messages");
        echo "</a>
                    </li>
                    ";
        // line 28
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["guideTypes"]) || array_key_exists("guideTypes", $context) ? $context["guideTypes"] : (function () { throw new RuntimeError('Variable "guideTypes" does not exist.', 28, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["guideType"]) {
            // line 29
            echo "                        <li class=\"nav-item\">
                            <button class=\"nav-link\" data-bs-toggle=\"tab\" data-bs-target=\"#section-";
            // line 30
            echo twig_escape_filter($this->env, twig_replace_filter($context["guideType"], [" " => "-"]), "html", null, true);
            echo "\" type=\"button\" role=\"tab\" aria-controls=\"section-";
            echo twig_escape_filter($this->env, $context["guideType"], "html", null, true);
            echo "\">
                            ";
            // line 31
            echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("%type% Guides", ["%type%" => $context["guideType"]], "messages");
            // line 32
            echo "                            </button>
                        </li>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['guideType'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 35
        echo "                    </ul>
                </div>
                <div class=\"tab-content\" id=\"pills-tabContent-guides\">
                <div class=\"tab-pane show active mt-4\" id=\"section-Collection\" role=\"tabpanel\">
                    <ul class=\"two-columns\">
                        ";
        // line 40
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["collections"]) || array_key_exists("collections", $context) ? $context["collections"] : (function () { throw new RuntimeError('Variable "collections" does not exist.', 40, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["collection"]) {
            // line 41
            echo "                            <li><a href=\"/subjects/collection.php?d=";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["collection"], "shortform", [], "any", false, false, false, 41), "html", null, true);
            echo "\">";
            echo twig_get_attribute($this->env, $this->source, $context["collection"], "title", [], "any", false, false, false, 41);
            echo "</a></li>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['collection'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 43
        echo "                    </ul>
                </div>
                ";
        // line 45
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["guideTypes"]) || array_key_exists("guideTypes", $context) ? $context["guideTypes"] : (function () { throw new RuntimeError('Variable "guideTypes" does not exist.', 45, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["guideType"]) {
            // line 46
            echo "                     <div class=\"tab-pane mt-4\" id=\"section-";
            echo twig_escape_filter($this->env, twig_replace_filter($context["guideType"], [" " => "-"]), "html", null, true);
            echo "\" role=\"tabpanel\">
                         <ul class=\"two-columns\">
                             ";
            // line 48
            $context["filteredGuides"] = twig_array_filter($this->env, (isset($context["guides"]) || array_key_exists("guides", $context) ? $context["guides"] : (function () { throw new RuntimeError('Variable "guides" does not exist.', 48, $this->source); })()), function ($__g__) use ($context, $macros) { $context["g"] = $__g__; return (0 === twig_compare(twig_get_attribute($this->env, $this->source, (isset($context["g"]) || array_key_exists("g", $context) ? $context["g"] : (function () { throw new RuntimeError('Variable "g" does not exist.', 48, $this->source); })()), "type", [], "array", false, false, false, 48), $context["guideType"])); });
            // line 49
            echo "                             ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["filteredGuides"]) || array_key_exists("filteredGuides", $context) ? $context["filteredGuides"] : (function () { throw new RuntimeError('Variable "filteredGuides" does not exist.', 49, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["guide"]) {
                // line 50
                echo "                                 <li><a href=\"";
                echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("guidebyShortname", ["shortform" => twig_get_attribute($this->env, $this->source, $context["guide"], "shortform", [], "any", false, false, false, 50)]), "html", null, true);
                echo "\">";
                echo twig_get_attribute($this->env, $this->source, $context["guide"], "subject", [], "any", false, false, false, 50);
                echo "</a></li>
                             ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['guide'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 52
            echo "                         </ul>
                     </div>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['guideType'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 55
        echo "                </div>
                </div>
<!-- TODO: extract the expert bubbles -->
                <div class=\"col-lg-4\">
                    <h4 class=\"mb-2\">Find an Expert</h4>
                    <p>Need help? Ask an expert!</p>
                    <ul class=\"people-vertical list-unstyled\">
\t\t\t\t\t\t                    </ul>

                    <div class=\"text-center mt-3 mb-3 mb-lg-5\"><a
                                href=\"http://localhost:8100/subjects/staff.php?letter=Subject Librarians\"
                                class=\"btn btn-default\" role=\"button\">See all experts</a></div>

                    <div class=\"feature-light popular-list p-3 mt-3\">
                        ";
        // line 69
        $this->loadTemplate("patron/shared/new_databases.html.twig", "patron/index.html.twig", 69)->display($context);
        // line 70
        echo "                        ";
        $this->loadTemplate("patron/index/new_guides.html.twig", "patron/index.html.twig", 70)->display($context);
        // line 71
        echo "                    </div>
                </div>
            </div>

        </div>
    </section>



";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

    }

    public function getTemplateName()
    {
        return "patron/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  246 => 71,  243 => 70,  241 => 69,  225 => 55,  217 => 52,  206 => 50,  201 => 49,  199 => 48,  193 => 46,  189 => 45,  185 => 43,  174 => 41,  170 => 40,  163 => 35,  155 => 32,  153 => 31,  147 => 30,  144 => 29,  140 => 28,  135 => 26,  121 => 14,  119 => 13,  116 => 12,  106 => 11,  94 => 8,  89 => 7,  79 => 6,  60 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends 'patron/base.html.twig' %}

{% block title %}Guide{% endblock %}


{% block javascripts %}
     {{ parent() }}
     {{ encore_entry_script_tags('patron_index') }}
{% endblock %}

{% block body %}

{% include 'patron/shared/searchbar.html.twig' with {'action': '/subjects/search.php', 'label': 'Find guides'} %}


<section class=\"section section-half-top\">
    <div class=\"container\">
        <div class=\"row\">
            <div class=\"col-lg-8\">
                <div class=\"pills-container mt-5\">
                    <ul class=\"nav nav-pills justify-content-around justify-content-md-start\" id=\"pills-tab-guides\" role=\"tablist\">
                    
                    <li class=\"nav-item\">
                        <a class=\"nav-link no-decoration default active\" id=\"show-Collection\" name=\"showCollection\" data-toggle=\"pill\"
                        href=\"#section-Collection\" role=\"tab\" aria-controls=\"section-Collection\"
                        aria-selected=\"true\">{% trans %}Guide Collections{% endtrans %}</a>
                    </li>
                    {% for guideType in guideTypes %}
                        <li class=\"nav-item\">
                            <button class=\"nav-link\" data-bs-toggle=\"tab\" data-bs-target=\"#section-{{guideType | replace({' ': '-'})}}\" type=\"button\" role=\"tab\" aria-controls=\"section-{{guideType}}\">
                            {% trans with {'%type%': guideType} %}%type% Guides{% endtrans %}
                            </button>
                        </li>
                    {% endfor %}
                    </ul>
                </div>
                <div class=\"tab-content\" id=\"pills-tabContent-guides\">
                <div class=\"tab-pane show active mt-4\" id=\"section-Collection\" role=\"tabpanel\">
                    <ul class=\"two-columns\">
                        {% for collection in collections %}
                            <li><a href=\"/subjects/collection.php?d={{collection.shortform}}\">{{collection.title | raw}}</a></li>
                        {% endfor %}
                    </ul>
                </div>
                {% for guideType in guideTypes %}
                     <div class=\"tab-pane mt-4\" id=\"section-{{guideType | replace({' ': '-'})}}\" role=\"tabpanel\">
                         <ul class=\"two-columns\">
                             {% set filteredGuides =  guides | filter(g => g['type'] == guideType) %}
                             {% for guide in filteredGuides %}
                                 <li><a href=\"{{ path('guidebyShortname', {shortform: guide.shortform}) }}\">{{ guide.subject | raw }}</a></li>
                             {% endfor %}
                         </ul>
                     </div>
                {% endfor %}
                </div>
                </div>
<!-- TODO: extract the expert bubbles -->
                <div class=\"col-lg-4\">
                    <h4 class=\"mb-2\">Find an Expert</h4>
                    <p>Need help? Ask an expert!</p>
                    <ul class=\"people-vertical list-unstyled\">
\t\t\t\t\t\t                    </ul>

                    <div class=\"text-center mt-3 mb-3 mb-lg-5\"><a
                                href=\"http://localhost:8100/subjects/staff.php?letter=Subject Librarians\"
                                class=\"btn btn-default\" role=\"button\">See all experts</a></div>

                    <div class=\"feature-light popular-list p-3 mt-3\">
                        {% include 'patron/shared/new_databases.html.twig' %}
                        {% include 'patron/index/new_guides.html.twig' %}
                    </div>
                </div>
            </div>

        </div>
    </section>



{% endblock %}", "patron/index.html.twig", "/Users/cbrownroberts/SubjectsPlus5/SP5-Docker-Symfony/sp5-docker/SubjectsPlus/templates/patron/index.html.twig");
    }
}
