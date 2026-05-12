import os

# XML Template for Draw.io
xml_template = """<mxfile>
  <diagram name="{name}">
    <mxGraphModel dx="1200" dy="1200" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="827" pageHeight="1169" math="0" shadow="0">
      <root>
        <mxCell id="0" />
        <mxCell id="1" parent="0" />
{content}
      </root>
    </mxGraphModel>
  </diagram>
</mxfile>"""

def escape_xml(text):
    return text.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;').replace('"', '&quot;').replace("'", '&apos;')

def node(id, value, style, x, y, w, h, parent="1"):
    safe_value = escape_xml(value)
    return f'        <mxCell id="{id}" value="{safe_value}" style="{style}" vertex="1" parent="{parent}"><mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry" /></mxCell>\n'

def edge(id, value, source, target, exitX=0.5, exitY=1, entryX=0.5, entryY=0, extra_pts="", parent="1"):
    safe_value = escape_xml(value)
    pts = f'<Array as="points">{extra_pts}</Array>' if extra_pts else ""
    return f'''        <mxCell id="{id}" value="{safe_value}" style="endArrow=classic;html=1;strokeColor=#000000;strokeWidth=1;rounded=0;labelBackgroundColor=#ffffff;exitX={exitX};exitY={exitY};entryX={entryX};entryY={entryY};" edge="1" parent="{parent}" source="{source}" target="{target}">
          <mxGeometry relative="1" as="geometry">{pts}</mxGeometry>
        </mxCell>\n'''

# Styles
s_lane = 'swimlane;html=1;childLayout=stackLayout;horizontal=1;startSize=30;horizontalStack=1;resizeParent=1;resizeParentMax=0;resizeLast=0;collapsible=1;marginBottom=0;'
s_sublane = 'swimlane;html=1;startSize=30;'
s_action = 'ellipse;whiteSpace=wrap;html=1;fillColor=#ffffff;strokeColor=#000000;verticalAlign=middle;'
s_decision = 'rhombus;whiteSpace=wrap;html=1;fillColor=#ffffff;strokeColor=#000000;'
s_start = 'ellipse;whiteSpace=wrap;html=1;aspect=fixed;fillColor=#000000;strokeColor=none;'
s_end = 'ellipse;html=1;shape=endState;fillColor=#000000;strokeColor=#000000;'

def clean_login_swimlane():
    c = node("pool", "Login & Reset Process", s_lane, 50, 50, 600, 900)
    c += node("lane_u", "User", s_sublane, 0, 0, 300, 900, "pool")
    c += node("lane_s", "System", s_sublane, 300, 0, 300, 900, "pool")
    
    # Vertically aligned nodes
    y_step = 80
    curr_y = 40
    
    c += node("start", "", s_start, 135, curr_y, 30, 30, "lane_u")
    curr_y += y_step
    c += node("u1", "Clicks login", s_action, 50, curr_y, 200, 60, "lane_u")
    curr_y += y_step
    c += node("u2", "Mobile/email & password", s_action, 50, curr_y, 200, 60, "lane_u")
    
    # System decision at same level as u2 or next
    c += node("d1", "Login Successful?", s_decision, 100, curr_y - 10, 100, 80, "lane_s")
    
    curr_y += y_step + 20
    c += node("u3", "Book Room/Car", s_action, 50, curr_y, 200, 60, "lane_u")
    c += node("s1", "Redirected to reset", s_action, 50, curr_y, 200, 60, "lane_s")
    
    curr_y += y_step
    c += node("u4", "Provides email", s_action, 50, curr_y, 200, 60, "lane_u")
    c += node("s2", "Click forgot password", s_action, 50, curr_y - 100, 150, 60, "lane_s") # Adjusted later
    
    curr_y += y_step
    c += node("u5", "Enter new password", s_action, 50, curr_y, 200, 60, "lane_u")
    
    curr_y += y_step
    c += node("u6", "Confirm password", s_action, 50, curr_y, 200, 60, "lane_u")
    
    curr_y += y_step
    c += node("s3", "Password reset successfully", s_action, 50, curr_y, 200, 60, "lane_s")
    
    curr_y += y_step
    c += node("end", "", s_end, 135, curr_y, 30, 30, "lane_s")

    # Clean Edges
    c += edge("e1", "", "start", "u1", parent="lane_u")
    c += edge("e2", "", "u1", "u2", parent="lane_u")
    c += edge("e3", "", "u2", "d1", 1, 0.5, 0, 0.5, parent="pool")
    c += edge("e4", "Yes", "d1", "u3", 0, 1, 1, 0.5, "<mxPoint x='350' y='330'/>", parent="pool")
    c += edge("e5", "No", "d1", "s1", 0.5, 1, 0.5, 0, parent="lane_s")
    c += edge("e6", "", "s1", "u4", 0, 0.5, 1, 0.5, parent="pool")
    c += edge("e7", "", "u4", "u5", parent="lane_u")
    c += edge("e8", "", "u5", "u6", parent="lane_u")
    c += edge("e9", "", "u6", "s3", 1, 0.5, 0, 0.5, parent="pool")
    c += edge("e10", "", "s3", "end", parent="lane_s")
    
    return xml_template.replace("{name}", "Login Swimlane").replace("{content}", c)

def clean_reg_swimlane():
    c = node("pool", "Registration Process", s_lane, 50, 50, 600, 800)
    c += node("lane_u", "User", s_sublane, 0, 0, 300, 800, "pool")
    c += node("lane_s", "System", s_sublane, 300, 0, 300, 800, "pool")
    
    y = 40
    c += node("start", "", s_start, 135, y, 30, 30, "lane_u")
    y += 80
    c += node("u1", "clicks on purchase", s_action, 50, y, 200, 60, "lane_u")
    c += node("s1", "Redirect to login/reg", s_action, 50, y, 200, 60, "lane_s")
    y += 100
    c += node("d1", "register?", s_decision, 100, y, 100, 80, "lane_s")
    y += 100
    c += node("u2", "fills out registration form", s_action, 50, y, 200, 60, "lane_u")
    c += node("s2", "login page", s_action, 150, y, 100, 60, "lane_s")
    y += 100
    c += node("u3", "provides mobile & NID", s_action, 50, y, 200, 60, "lane_u")
    y += 100
    c += node("s3", "Submit form", s_action, 50, y, 200, 60, "lane_s")
    y += 100
    c += node("u4", "Successfully registered", s_action, 50, y, 200, 60, "lane_u")
    y += 80
    c += node("end", "", s_end, 135, y, 30, 30, "lane_s")

    c += edge("e1", "", "start", "u1", parent="lane_u")
    c += edge("e2", "", "u1", "s1", 1, 0.5, 0, 0.5, parent="pool")
    c += edge("e3", "", "s1", "d1", parent="lane_s")
    c += edge("e4", "No", "d1", "s2", 1, 0.5, 0.5, 0, "<mxPoint x='230' y='260'/>", parent="lane_s")
    c += edge("e5", "Yes", "d1", "u2", 0, 0.5, 1, 0.5, parent="pool")
    c += edge("e6", "", "u2", "u3", parent="lane_u")
    c += edge("e7", "", "u3", "s3", 1, 0.5, 0, 0.5, parent="pool")
    c += edge("e8", "", "s3", "u4", 0, 0.5, 1, 0.5, parent="pool")
    c += edge("e9", "", "s2", "end", parent="lane_s")
    c += edge("e10", "", "u4", "end", 1, 0.5, 0, 0.5, "<mxPoint x='280' y='770'/><mxPoint x='350' y='770'/>", parent="pool")
    
    return xml_template.replace("{name}", "Registration Swimlane").replace("{content}", c)

# Update the files
with open("Grand_Azure_Login_Swimlane.drawio", "w") as f:
    f.write(clean_login_swimlane())
with open("Grand_Azure_Registration_Swimlane.drawio", "w") as f:
    f.write(clean_reg_swimlane())
# Add other cleanups if necessary
