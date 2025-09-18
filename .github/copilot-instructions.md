# 1. GitHub Copilot Instruction Guide

- Organize files by feature and responsibility, following the current directory structure.
- Keep files under 500 lines for maintainability.
- Use namespaces and PSR-4 for autoloading.
- Separate domain logic (app/Core, app/Services, app/Models) from templates (resources/templates) and assets (public/assets).
- Centralize configuration in `app/Helpers/ConfigurationSystem.php` and reference keys in `app/Config/Defaults/ServerConfigDefaults.php`.
- Document configuration changes in `CONFIG_CHANGE.md`.
- Use `app/Plugins/AdminDashboard` for admin panel features.
- Use `resources/templates/dashboard` for shared templates and `resources/templates/partials` for reusable components.

## 2. Convenções Gerais

1. **Código Autocontido**: Todo trecho de código deve ser completo e testável, inclusive importações e dependências.
2. **Dependências**: Se for criar um projeto do zero, gere um arquivo de dependências (e.g., `package.json`, `requirements.txt`) com versões específicas.
3. **Comentários**: Use comentários claros e concisos em inglês para explicar decisões não triviais.
4. **Pensamento Algorítmico Superior**: Ao propor soluções, priorize eficiência computacional (Big O otimizado) e escalabilidade.
5. **Arquitetura de Elite**: Sugira padrões de design avançados quando apropriado (CQRS, Event Sourcing, DDD).

## 3. Edição de Arquivos Existentes

- Nunca sobrescrever arquivos inteiros sem contexto.
- Ao modificar um arquivo:
    1. Insira apenas o bloco de código alterado.
    2. Use marcadores (`// ... existing code ...`) para indicar trechos não alterados.
- Analise profundamente as dependências e impactos da modificação.
- Sugira refatorações quando uma alteração pontual reduzir a qualidade da base de código.

## 4. Estrutura de Planos de Ação

- Para tarefas de configuração ou deploy, o Copilot deve sugerir comandos completos de terminal, incluindo:
    - `cd` para o diretório correto.
    - Ferramentas de paginação (`| cat`).
- Exemplos de comandos devem estar prontos para cópia e colagem.
- Forneça scripts automatizados sempre que possível para reduzir trabalho manual.
- Inclua verificações de sanidade antes e depois das operações críticas.

## 5. Busca e Leitura de Conteúdos

- Prefira:
    1. **Pesquisa Semântica** para localizar trechos de lógica compartilhada.
    2. **Pesquisa Exata** (grep) para símbolos ou padrões específicos.
- Ao ler arquivos grandes (>250 linhas), busque apenas a parte relevante, especificando intervalo de linhas.
- Analise padrões de código recorrentes para identificar oportunidades de abstração.
- Considere sempre o contexto amplo do projeto ao propor soluções localizadas.

## 6. Propostas de Comandos de Terminal

- Sempre explique brevemente por que o comando é necessário antes de sugeri-lo.
- Comandos longos que não exigem interação devem ser executados em background:
  ```bash
  some_long_running_task &
  ```
- Forneça scripts com verificações intermediárias para procedimentos complexos.
- Use ferramentas avançadas de diagnóstico e performance (strace, perf, etc.).

## 7. Security-Database

- Store credentials in config files outside version control.
- Always sanitize and validate user input (use `app/Core/Validator.php`).
- Escape all HTML output to prevent XSS.
- Use transactions for multi-table operations.
- Create indexes for query optimization.
- Document database schema in SQL files under `database/schema`.
- Implemente proteções contra ataques de injeção SQL, CSRF e DDoS.
- Sugira estratégias de cache eficientes para queries frequentes.
- Identifique e otimize bottlenecks de performance em queries complexas.

## 8. Documentation

- Update `README.md` when features or dependencies change.
- Comment complex code with "// Reason:" explanations.
- Document SQL Server configuration and integrations.
- Mark completed tasks in `TASK.md` immediately.
- Consult `PLANNING.md` before starting any task.
- Forneça diagramas conceituais em formato ASCII para explicar arquiteturas complexas.
- Documente decisões importantes com formato ADR (Architecture Decision Record).
- Adicione exemplos práticos e casos de uso para APIs e interfaces públicas.

## 9. Development

- Ask questions when context is unclear.
- Use only verified and PHP-version-compatible packages.
- Confirm file paths and class names before referencing.
- Do not delete existing code without explicit instruction.
- Verify PHP version compatibility before using new features.
- Aplique análise algorítmica sofisticada para otimizar código crítico.
- Sugira testes de unidade e integração para novas implementações.
- Proponha refatorações modulares para reduzir acoplamento e melhorar coesão.
- Identifique padrões de anti-design e proponha correções arquiteturais.

## 10. Frontend-Design

- This is a MuOnline project: create components with a gaming purpose.
- For texts in Twig templates, use `{{ "Text Here"|trans }}`.
- For texts in JSON responses, use `__("Text here")`.
- For AJAX requests, use `app.ajaxy(ROUTE, 'GET OR POST', jsonData, refreshPage, [callback])`.
- Follow the visual pattern of existing modules for consistency.
- Use partials for reusable components.
- Implemente otimizações avançadas de renderização para interfaces dinâmicas.
- Sugira técnicas de lazy-loading e code-splitting para melhorar performance.
- Incorpore princípios de UX focados em jogabilidade e retenção de usuários.
- Proponha testes A/B para elementos críticos da interface.

## 11. Análise de Performance e Otimização

- Identifique gargalos de performance através de análise profunda de código.
- Sugira otimizações de algoritmos com análise de complexidade.
- Proponha estratégias de cache multicamada (memory, disk, CDN).
- Recomende otimizações de assets (minificação, compressão, sprites).
- Analise e otimize consultas SQL para máxima eficiência.
- Sugira configurações avançadas de PHP-FPM, OPcache e Nginx para escala.

## 12. Arquitetura e Escalabilidade

- Avalie oportunidades para microserviços onde apropriado.
- Sugira padrões de mensageria para operações assíncronas.
- Identifique componentes que se beneficiariam de maior isolamento.
- Proponha estratégias de sharding para dados de alto volume.
- Recomende técnicas de circuit breaking para garantir resiliência.
- Desenvolva arquiteturas event-driven para componentes desacoplados.
- Aplique princípios SOLID e DDD para garantir escalabilidade do código.

## 13. Integração e DevOps

- Sugira pipelines CI/CD apropriados para o contexto do projeto.
- Recomende estratégias de versionamento para APIs e interfaces públicas.
- Proponha processos de blue/green deployment para atualizações sem downtime.
- Crie scripts de automação para tarefas repetitivas.
- Desenvolva estratégias de monitoramento proativo para falhas e degradações.
- Implemente verificações de saúde inteligentes para componentes críticos.

## 14. Otimização Profunda de Código

- Utilize análise estática para identificar problemas não aparentes.
- Recomende refatorações focadas em princípios matemáticos e algorítmicos avançados.
- Identifique oportunidades para paralelismo e concorrência em código crítico.
- Proponha estruturas de dados especializadas para casos específicos de uso.
- Sugira implementações personalizadas para substituir bibliotecas genéricas em áreas críticas.
- Identifique e elimine recursões excessivas, loops desnecessários e operações redundantes.

## 15. Inteligência Estratégica

- Ao solucionar problemas, pense além da solução imediata e considere o impacto de longo prazo.
- Avalie trade-offs entre desempenho, manutenibilidade e segurança com análises detalhadas.
- Proponha abordagens disruptivas para problemas aparentemente simples quando apropriado.
- Identifique oportunidades para melhorias sistemáticas além do escopo original da tarefa.
- Desenvolva modelos mentais sofisticados para prever desafios futuros do projeto.
- Antecipe tendências tecnológicas e sugira adaptações proativas da arquitetura.
