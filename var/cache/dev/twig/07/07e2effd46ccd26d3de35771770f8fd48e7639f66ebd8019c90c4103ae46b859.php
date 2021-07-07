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

/* patron/shared/searchbar.html.twig */
class __TwigTemplate_cf51fd2f0862f6f4541ae5bed44ea79ade3c165f580fcb2bf44eb34f5807905a extends Template
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
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patron/shared/searchbar.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patron/shared/searchbar.html.twig"));

        // line 1
        echo "<div class=\"section section-half\">
    <div class=\"container\">
        <div class=\"row\">
            <div class=\"col-sm-10 col-lg-8 offset-sm-1 offset-lg-2\">
                <form action=\"";
        // line 5
        echo twig_escape_filter($this->env, (isset($context["action"]) || array_key_exists("action", $context) ? $context["action"] : (function () { throw new RuntimeError('Variable "action" does not exist.', 5, $this->source); })()), "html", null, true);
        echo "\" method=\"post\" class=\"mt-5\" id=\"sp_admin_search\">
                    <div class=\"input-group\">
                        <div id=\"my-autocomplete-container\"></div>
                        <input type=\"submit\" value=\"Go\"  class=\"btn btn-primary\" id=\"topsearch_button\" name=\"submitsearch\" />
                    </div>
                    <label for=\"my-autocomplete\" class=\"visually-hidden\">";
        // line 10
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\TranslationExtension']->trans((isset($context["label"]) || array_key_exists("label", $context) ? $context["label"] : (function () { throw new RuntimeError('Variable "label" does not exist.', 10, $this->source); })())), "html", null, true);
        echo "</label>
                </form>
            </div>
        </div>
    </div>
</div>
";
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "patron/shared/searchbar.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  57 => 10,  49 => 5,  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<div class=\"section section-half\">
    <div class=\"container\">
        <div class=\"row\">
            <div class=\"col-sm-10 col-lg-8 offset-sm-1 offset-lg-2\">
                <form action=\"{{action}}\" method=\"post\" class=\"mt-5\" id=\"sp_admin_search\">
                    <div class=\"input-group\">
                        <div id=\"my-autocomplete-container\"></div>
                        <input type=\"submit\" value=\"Go\"  class=\"btn btn-primary\" id=\"topsearch_button\" name=\"submitsearch\" />
                    </div>
                    <label for=\"my-autocomplete\" class=\"visually-hidden\">{{ label | trans }}</label>
                </form>
            </div>
        </div>
    </div>
</div>
", "patron/shared/searchbar.html.twig", "/home/site/wwwroot/templates/patron/shared/searchbar.html.twig");
    }
}
