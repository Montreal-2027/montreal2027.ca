```mermaid
graph LR
    subgraph webteam[Technology & Production Division]
        direction RL
        lead[Website Principal Team Lead]
        lead---backend[Backend Developers]
        lead---frontend[Frontend Developers]
        lead---content[Webteam Content Entry]
        lead-.-ct[Committee & Team Page]


    end

    subgraph comms[Communictions Division]
        direction LR
        pm[Project Manager]
        pm---graphics[Graphics Creative Services]
        pm---copyedit[FR & EN copy editing]
        pm---cc[Text Content Creation]
        pm-.-ft[French Translations]
    end

webteam-.-webt[Website Translations]---comms
webteam-.-im[In Memoriam]---comms
```
