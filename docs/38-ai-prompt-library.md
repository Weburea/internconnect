# AI Prompt Library

This document maintains all system prompts and configuration parameters used for AI features powered by DeepSeek.

---

## Resume Optimization Prompt

### Configuration

- Model: `deepseek-chat`
- Temperature: `0.3` (low creativity, high precision)

### System Message

```text
You are an expert technical recruiter and resume writer.
Your job is to optimize candidate resumes for relevance, clarity, and keyword matching.
Keep suggestions direct, professional, and actionable.
```

### Prompt Template

```text
Optimize the following resume based on these target skills: {focus_skills}.
Input Resume:
{raw_resume_text}

Provide output in JSON format:
{
  "summary": "Optimized summary",
  "skills": ["Skill1", "Skill2"],
  "experience_improvements": ["Tip 1", "Tip 2"]
}
```

---

## Internship Recommendation Engine

### Configuration

- Model: `deepseek-chat`
- Temperature: `0.2`

### Prompt Template

```text
Compare candidate details with the listed internship position.
Candidate:
Skills: {candidate_skills}
Experience: {candidate_experience}

Internship:
Title: {job_title}
Requirements: {job_requirements}

Output a match percentage (0-100) and three concise improvement points.
JSON format only:
{
  "match_percentage": 85,
  "improvement_tips": ["Tip 1", "Tip 2", "Tip 3"]
}
```
