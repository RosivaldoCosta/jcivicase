using Microsoft.Practices.SmartClient.DisconnectedAgent;
using System;
using Sante.EMR.SmartClient.OutBox.DataWebService;

namespace Sante.EMR.SmartClient.OutBox.DSA.DataService
{
    // Generated code for the web service.
    // Use this proxy to make requests to the service when working in an application that is occasionally connected
    public abstract class CallbackBase
    {
        #region HelloWorld

        public abstract void OnHelloWorldReturn(Request request, object[] parameters, String returnValue);

        public abstract OnExceptionAction OnHelloWorldException(Request request, Exception ex);

        public abstract void OnSendFormsReturn(Request request, object[] parameters, Form[] returnValue);

        #endregion HelloWorld

        #region HelloWorldAsync

        public abstract void OnHelloWorldAsync1Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnHelloWorldAsync1Exception(Request request, Exception ex);

        #endregion HelloWorldAsync

        #region HelloWorldAsync

        public abstract void OnHelloWorldAsync2Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnHelloWorldAsync2Exception(Request request, Exception ex);

        #endregion HelloWorldAsync

        #region GetModifiedTables

        public abstract void OnGetModifiedTablesReturn(Request request, object[] parameters, String[] returnValue);

        public abstract OnExceptionAction OnGetModifiedTablesException(Request request, Exception ex);

        #endregion GetModifiedTables

        #region GetModifiedTablesAsync

        public abstract void OnGetModifiedTablesAsync1Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnGetModifiedTablesAsync1Exception(Request request, Exception ex);

        #endregion GetModifiedTablesAsync

        #region GetModifiedTablesAsync

        public abstract void OnGetModifiedTablesAsync2Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnGetModifiedTablesAsync2Exception(Request request, Exception ex);

        #endregion GetModifiedTablesAsync

        #region Search

        public abstract void OnSearchReturn(Request request, object[] parameters, Patient[] returnValue);

        public abstract OnExceptionAction OnSearchException(Request request, Exception ex);

        #endregion Search

        #region SearchAsync

        public abstract void OnSearchAsync1Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnSearchAsync1Exception(Request request, Exception ex);

        #endregion SearchAsync

        #region SearchAsync

        public abstract void OnSearchAsync2Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnSearchAsync2Exception(Request request, Exception ex);

        #endregion SearchAsync

        #region GetTasks

        public abstract void OnGetTasksReturn(Request request, object[] parameters, NextTask[] returnValue);

        public abstract OnExceptionAction OnGetTasksException(Request request, Exception ex);

        #endregion GetTasks

        #region GetTasksAsync

        public abstract void OnGetTasksAsync1Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnGetTasksAsync1Exception(Request request, Exception ex);

        #endregion GetTasksAsync

        #region GetTasksAsync

        public abstract void OnGetTasksAsync2Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnGetTasksAsync2Exception(Request request, Exception ex);

        #endregion GetTasksAsync

        #region GetOPSCases

        public abstract void OnGetOPSCasesReturn(Request request, object[] parameters, Case[] returnValue);

        public abstract OnExceptionAction OnGetOPSCasesException(Request request, Exception ex);

        #endregion GetOPSCases

        #region GetOPSCasesAsync

        public abstract void OnGetOPSCasesAsync1Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnGetOPSCasesAsync1Exception(Request request, Exception ex);

        #endregion GetOPSCasesAsync

        #region GetOPSCasesAsync

        public abstract void OnGetOPSCasesAsync2Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnGetOPSCasesAsync2Exception(Request request, Exception ex);

        #endregion GetOPSCasesAsync

        #region RetrieveMCTCases

        public abstract void OnRetrieveMCTCasesReturn(Request request, object[] parameters, Boolean returnValue);

        public abstract OnExceptionAction OnRetrieveMCTCasesException(Request request, Exception ex);

        #endregion RetrieveMCTCases

        #region RetrieveMCTCasesAsync

        public abstract void OnRetrieveMCTCasesAsync1Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnRetrieveMCTCasesAsync1Exception(Request request, Exception ex);

        #endregion RetrieveMCTCasesAsync

        #region RetrieveMCTCasesAsync

        public abstract void OnRetrieveMCTCasesAsync2Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnRetrieveMCTCasesAsync2Exception(Request request, Exception ex);

        #endregion RetrieveMCTCasesAsync

        #region RetrieveIHITCases

        public abstract void OnRetrieveIHITCasesReturn(Request request, object[] parameters, Boolean returnValue);

        public abstract OnExceptionAction OnRetrieveIHITCasesException(Request request, Exception ex);

        #endregion RetrieveIHITCases

        #region RetrieveIHITCasesAsync

        public abstract void OnRetrieveIHITCasesAsync1Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnRetrieveIHITCasesAsync1Exception(Request request, Exception ex);

        #endregion RetrieveIHITCasesAsync

        #region RetrieveIHITCasesAsync

        public abstract void OnRetrieveIHITCasesAsync2Return(Request request, object[] parameters);

        public abstract OnExceptionAction OnRetrieveIHITCasesAsync2Exception(Request request, Exception ex);

        #endregion RetrieveIHITCasesAsync

        #region CancelAsync

        public abstract void OnCancelAsyncReturn(Request request, object[] parameters);

        public abstract OnExceptionAction OnCancelAsyncException(Request request, Exception ex);

        #endregion CancelAsync

    }
}
